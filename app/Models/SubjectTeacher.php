<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SubjectTeacher extends Model
{
    protected  $table = 'subject_teacher';
    protected $guarded = [];

    protected $casts = [
        'heures_semaine' => 'decimal:2',
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
     * Calculate actual weekly hours from emploi_temps
     * @return float
     */
    public function getHeuresReellesAttribute(): float
    {
        $emplois = EmploiTemps::where('teacher_id', $this->teacher_id)
            ->where('class_id', $this->class_id)
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
     * Calculate progress percentage (taux)
     * @return float
     */
    public function getTauxAttribute(): float
    {
        if (!$this->heures_semaine || $this->heures_semaine == 0) {
            return 0;
        }

        $heuresReelles = $this->heures_reelles;
        return round(($heuresReelles / $this->heures_semaine) * 100, 1);
    }

    /**
     * Get remaining hours
     * @return float
     */
    public function getHeuresRestantesAttribute(): float
    {
        if (!$this->heures_semaine) {
            return 0;
        }

        return max(0, $this->heures_semaine - $this->heures_reelles);
    }

    /**
     * Check if hours limit is exceeded
     * @return bool
     */
    public function getIsDepasseAttribute(): bool
    {
        if (!$this->heures_semaine) {
            return false;
        }

        return $this->heures_reelles > $this->heures_semaine;
    }

    /**
     * Get status label
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
     * Get all assignments for a teacher in current year with hours info
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
                    'heures_semaine' => $assignment->heures_semaine ?? 0,
                    'heures_reelles' => $assignment->heures_reelles,
                    'heures_restantes' => $assignment->heures_restantes,
                    'taux' => $assignment->taux,
                    'statut' => $assignment->statut_heures,
                    'is_depasse' => $assignment->is_depasse,
                ];
            });
    }

    /**
     * Get total weekly hours for a teacher across all classes in a trimester
     */
    public static function getTotalWeeklyHours($teacherId, $trimesterId, $anneeId = null)
    {
        $anneeId = $anneeId ?? Anneescolaire::where('is_active', 1)->value('id');

        $assignments = self::where('teacher_id', $teacherId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->get();

        $totalAssigned = $assignments->sum('heures_semaine');
        $totalActual = $assignments->sum(function ($a) {
            return $a->heures_reelles;
        });

        return [
            'heures_assignees' => $totalAssigned,
            'heures_reelles' => $totalActual,
            'taux' => $totalAssigned > 0 ? round(($totalActual / $totalAssigned) * 100, 1) : 0,
        ];
    }
}
