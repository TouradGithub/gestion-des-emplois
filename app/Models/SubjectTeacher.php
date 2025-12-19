<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SubjectTeacher extends Model
{
    protected  $table = 'subject_teacher';
    protected $guarded = [];

    protected $casts = [
        'heures_trimestre' => 'decimal:2',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class , 'subject_id' );
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class , 'teacher_id' );
    }
    public function annee()
    {
        return $this->belongsTo(Anneescolaire::class , 'annee_id' );
    }
    public function trimester()
    {
        return $this->belongsTo(Trimester::class , 'trimester_id' );
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    /**
     * Get emploi temps entries for this assignment
     */
    public function emploisTemps()
    {
        return EmploiTemps::where('teacher_id', $this->teacher_id)
            ->where('class_id', $this->class_id)
            ->where('trimester_id', $this->trimester_id)
            ->where('annee_id', $this->annee_id);
    }

    /**
     * Calculer le nombre total d'heures effectuées dans le trimestre
     * basé sur les séances programmées et les semaines du trimestre
     * @return float
     */
    public function getHeuresEffectueesAttribute(): float
    {
        $emplois = EmploiTemps::where('teacher_id', $this->teacher_id)
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->where('trimester_id', $this->trimester_id)
            ->where('annee_id', $this->annee_id)
            ->with('ref_horaires')
            ->get();

        $totalMinutesParSemaine = 0;

        foreach ($emplois as $emploi) {
            foreach ($emploi->ref_horaires as $horaire) {
                if ($horaire->start_time && $horaire->end_time) {
                    $start = Carbon::parse($horaire->start_time);
                    $end = Carbon::parse($horaire->end_time);
                    $totalMinutesParSemaine += $end->diffInMinutes($start);
                }
            }
        }

        // Heures par semaine
        $heuresParSemaine = round($totalMinutesParSemaine / 60, 2);

        // Calculer le nombre de semaines dans le trimestre (environ 12-14 semaines)
        $nombreSemaines = $this->getNombreSemainesTrimestre();

        return round($heuresParSemaine * $nombreSemaines, 2);
    }

    /**
     * Calculer les heures par semaine (pour affichage)
     * @return float
     */
    public function getHeuresParSemaineAttribute(): float
    {
        $emplois = EmploiTemps::where('teacher_id', $this->teacher_id)
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->where('trimester_id', $this->trimester_id)
            ->where('annee_id', $this->annee_id)
            ->with('ref_horaires')
            ->get();

        $totalMinutes = 0;

        foreach ($emplois as $emploi) {
            foreach ($emploi->ref_horaires as $horaire) {
                if ($horaire->start_time && $horaire->end_time) {
                    $start = Carbon::parse($horaire->start_time);
                    $end = Carbon::parse($horaire->end_time);
                    $totalMinutes += $end->diffInMinutes($start);
                }
            }
        }

        return round($totalMinutes / 60, 2);
    }

    /**
     * Obtenir le nombre de semaines dans le trimestre
     * @return int
     */
    public function getNombreSemainesTrimestre(): int
    {
        // Si le trimestre a des dates, calculer le nombre de semaines
        if ($this->trimester && $this->trimester->start_date && $this->trimester->end_date) {
            $start = Carbon::parse($this->trimester->start_date);
            $end = Carbon::parse($this->trimester->end_date);
            return max(1, $start->diffInWeeks($end));
        }

        // Par défaut, environ 12 semaines par trimestre
        return 12;
    }

    /**
     * Calculer le taux de progression (pourcentage)
     * @return float
     */
    public function getTauxAttribute(): float
    {
        if (!$this->heures_trimestre || $this->heures_trimestre == 0) {
            return 0;
        }

        $heuresEffectuees = $this->heures_effectuees;
        return round(($heuresEffectuees / $this->heures_trimestre) * 100, 1);
    }

    /**
     * Obtenir les heures restantes
     * @return float
     */
    public function getHeuresRestantesAttribute(): float
    {
        if (!$this->heures_trimestre) {
            return 0;
        }

        return max(0, $this->heures_trimestre - $this->heures_effectuees);
    }

    /**
     * Vérifier si le quota d'heures est dépassé
     * @return bool
     */
    public function getIsDepasseAttribute(): bool
    {
        if (!$this->heures_trimestre) {
            return false;
        }

        return $this->heures_effectuees > $this->heures_trimestre;
    }

    /**
     * Obtenir le statut des heures
     * @return string
     */
    public function getStatutHeuresAttribute(): string
    {
        $taux = $this->taux;

        if ($taux == 0) {
            return 'non_defini';
        } elseif ($taux < 50) {
            return 'en_retard';
        } elseif ($taux < 100) {
            return 'en_cours';
        } elseif ($taux == 100) {
            return 'complet';
        } else {
            return 'depasse';
        }
    }

    /**
     * Obtenir le résumé des heures pour un enseignant
     */
    public static function getTeacherHoursSummary($teacherId, $anneeId = null)
    {
        $anneeId = $anneeId ?? Anneescolaire::where('is_active', 1)->value('id');

        return self::where('teacher_id', $teacherId)
            ->where('annee_id', $anneeId)
            ->with(['classe', 'subject', 'trimester'])
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'classe' => $assignment->classe->nom ?? '-',
                    'subject' => $assignment->subject->name ?? '-',
                    'trimester' => $assignment->trimester->name ?? '-',
                    'heures_trimestre' => $assignment->heures_trimestre ?? 0,
                    'heures_effectuees' => $assignment->heures_effectuees,
                    'heures_par_semaine' => $assignment->heures_par_semaine,
                    'heures_restantes' => $assignment->heures_restantes,
                    'taux' => $assignment->taux,
                    'statut' => $assignment->statut_heures,
                    'is_depasse' => $assignment->is_depasse,
                ];
            });
    }

    /**
     * Obtenir le total des heures pour un enseignant dans un trimestre
     */
    public static function getTotalTrimesterHours($teacherId, $trimesterId, $anneeId = null)
    {
        $anneeId = $anneeId ?? Anneescolaire::where('is_active', 1)->value('id');

        $assignments = self::where('teacher_id', $teacherId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->get();

        $totalAssigned = $assignments->sum('heures_trimestre');
        $totalEffectuees = $assignments->sum(function ($a) {
            return $a->heures_effectuees;
        });

        return [
            'heures_assignees' => $totalAssigned,
            'heures_effectuees' => $totalEffectuees,
            'taux' => $totalAssigned > 0 ? round(($totalEffectuees / $totalAssigned) * 100, 1) : 0,
        ];
    }
}
