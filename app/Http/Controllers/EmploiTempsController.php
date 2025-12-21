<?php
namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\EmploiHoraire;
use App\Models\EmploiTemps;
use App\Models\Horaire;
use App\Models\Jour;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmploiTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emplois = EmploiTemps::with(['classe', 'subject', 'teacher', 'horairess'])->get();
        return view('admin.emplois.index', compact('emplois'));
    }

    /**
     * Afficher le calendrier des emplois du temps
     */
    public function showCalender()
    {
        $anneeActive = Anneescolaire::where('is_active', true)->first();
        if (!$anneeActive) {
            return redirect()->back()->with('error', 'Aucune année scolaire active trouvée.');
        }

        $classes_of_this_year = Classe::where('annee_id', $anneeActive->id)->orderBy('nom')->get();
        $start_time_calandrie = Carbon::createFromTimeString('08:00:00')->format('H:i:s');
        $end_time_calandrie = Carbon::createFromTimeString('19:00:00')->format('H:i:s');

        return view('admin.emplois.fullcalender.index', compact('classes_of_this_year', 'start_time_calandrie', 'end_time_calandrie'));
    }

    /**
     * Récupérer les données de référence (jours, horaires, salles)
     */
    public function getReferenceData()
    {
        $jours = Jour::orderBy('ordre')->get();
        $horaires = Horaire::orderBy('ordre')->get();
        $salles = \App\Models\SalleDeClasse::orderBy('name')->get();

        return response()->json([
            'jours' => $jours,
            'horaires' => $horaires,
            'salles' => $salles
        ]);
    }

    /**
     * Récupérer les événements du calendrier pour une classe et un trimestre
     */
    public function getCalendarEvents(Request $request)
    {
        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;
        $start = $request->start;
        $end = $request->end;

        if (!$classId || !$trimesterId) {
            return response()->json(['events' => []]);
        }

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        $emplois = EmploiTemps::with(['subject.subjectType', 'teacher', 'jour', 'ref_horaires', 'salle'])
            ->where('class_id', $classId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->get();

        $events = [];
        $colorIndex = 0;
        $subjectColors = [];

        foreach ($emplois as $emploi) {
            // Attribuer une couleur par matière
            if (!isset($subjectColors[$emploi->subject_id])) {
                $subjectColors[$emploi->subject_id] = $colorIndex++;
            }

            // Obtenir les horaires de début et de fin
            $horairesList = $emploi->ref_horaires()->orderBy('ordre')->get();
            if ($horairesList->isEmpty()) {
                continue;
            }

            $startTime = $horairesList->first()->start_time;
            $endTime = $horairesList->last()->end_time;

            // Mapper le jour de la semaine
            $jourOrdre = $emploi->jour->ordre ?? 1;
            $dayMapping = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 0];
            $dayOfWeek = $dayMapping[$jourOrdre] ?? 1;

            // Calculer la date de l'événement
            $startDate = Carbon::parse($start);
            while ($startDate->dayOfWeek != $dayOfWeek) {
                $startDate->addDay();
            }

            $eventStart = $startDate->format('Y-m-d') . 'T' . $startTime;
            $eventEnd = $startDate->format('Y-m-d') . 'T' . $endTime;

            $title = $emploi->subject->name ?? 'Matière';
            $prof = $emploi->teacher->name ?? 'Enseignant';
            $salle = $emploi->salle->name ?? '';

            // Récupérer le type de la matière
            $subjectType = null;
            if ($emploi->subject && $emploi->subject->subjectType) {
                $subjectType = [
                    'id' => $emploi->subject->subjectType->id,
                    'name' => $emploi->subject->subjectType->name,
                    'code' => $emploi->subject->subjectType->code,
                    'color' => $emploi->subject->subjectType->color
                ];
            }

            $events[] = [
                'id' => $emploi->id,
                'title' => $title,
                'start' => $eventStart,
                'end' => $eventEnd,
                'extendedProps' => [
                    'colorIndex' => $subjectColors[$emploi->subject_id],
                    'matiere' => $title,
                    'prof' => $prof,
                    'salle' => $salle,
                    'subject_type' => $subjectType
                ]
            ];
        }

        return response()->json(['events' => $events]);
    }

    /**
     * Récupérer les détails d'une séance
     */
    public function getCalendarEvent($id)
    {
        $emploi = EmploiTemps::with(['ref_horaires'])->find($id);

        if (!$emploi) {
            return response()->json(['success' => false, 'message' => 'Séance non trouvée.']);
        }

        return response()->json([
            'success' => true,
            'seance' => [
                'id' => $emploi->id,
                'class_id' => $emploi->class_id,
                'subject_id' => $emploi->subject_id,
                'teacher_id' => $emploi->teacher_id,
                'trimester_id' => $emploi->trimester_id,
                'jour_id' => $emploi->jour_id,
                'salle_de_classe_id' => $emploi->salle_de_classe_id,
                'horaire_ids' => $emploi->ref_horaires->pluck('id')->toArray()
            ]
        ]);
    }

    /**
     * Créer une nouvelle séance via le calendrier
     */
    public function storeCalendarEvent(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'jour_id' => 'required|exists:sct_refjours,id',
            'horaire_ids' => 'required|array|min:1',
            'horaire_ids.*' => 'required|exists:sct_ref_horaires,id',
            'salle_de_classe_id' => 'nullable|exists:salle_de_classes,id',
        ], [
            'class_id.required' => 'La classe est obligatoire.',
            'subject_id.required' => 'La matière est obligatoire.',
            'teacher_id.required' => 'L\'enseignant est obligatoire.',
            'trimester_id.required' => 'Le trimestre est obligatoire.',
            'jour_id.required' => 'Le jour est obligatoire.',
            'horaire_ids.required' => 'Au moins un horaire est obligatoire.',
        ]);

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;
        if (!$anneeId) {
            return response()->json(['success' => false, 'message' => 'Aucune année scolaire active trouvée.'], 400);
        }

        // Vérification de la capacité de la salle
        if ($request->salle_de_classe_id) {
            $classe = Classe::find($request->class_id);
            $salle = \App\Models\SalleDeClasse::find($request->salle_de_classe_id);
            $classCapacity = $classe->capacity ?? 20;

            if ($salle && $salle->capacity < $classCapacity) {
                return response()->json([
                    'success' => false,
                    'message' => "La capacité de la salle ({$salle->capacity} places) est inférieure à celle de la classe ({$classCapacity} étudiants)."
                ], 422);
            }
        }

        // Vérification des conflits
        $errors = $this->checkConflicts($request, $anneeId, null);
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'conflicts' => $errors,
                'message' => 'Des conflits ont été détectés.'
            ], 422);
        }

        // Créer la séance
        $emploi = EmploiTemps::create([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'trimester_id' => $request->trimester_id,
            'annee_id' => $anneeId,
            'jour_id' => $request->jour_id,
            'salle_de_classe_id' => $request->salle_de_classe_id,
        ]);

        // Ajouter les horaires
        foreach ($request->horaire_ids as $horaireId) {
            EmploiHoraire::create([
                'emploi_temps_id' => $emploi->id,
                'horaire_id' => $horaireId,
            ]);
        }

        // Envoyer notification aux étudiants de la classe
        NotificationService::notifyScheduleCreated($emploi);

        return response()->json([
            'success' => true,
            'message' => 'Séance créée avec succès.',
            'id' => $emploi->id
        ]);
    }

    /**
     * Mettre à jour une séance via le calendrier
     */
    public function updateCalendarEvent(Request $request, $id)
    {
        $emploi = EmploiTemps::find($id);
        if (!$emploi) {
            return response()->json(['success' => false, 'message' => 'Séance non trouvée.'], 404);
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'jour_id' => 'required|exists:sct_refjours,id',
            'horaire_ids' => 'required|array|min:1',
            'horaire_ids.*' => 'required|exists:sct_ref_horaires,id',
            'salle_de_classe_id' => 'nullable|exists:salle_de_classes,id',
        ]);

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        // Vérification des conflits (en excluant la séance actuelle)
        $errors = $this->checkConflicts($request, $anneeId, $id);
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'conflicts' => $errors,
                'message' => 'Des conflits ont été détectés.'
            ], 422);
        }

        // Mettre à jour la séance
        $emploi->update([
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'jour_id' => $request->jour_id,
            'salle_de_classe_id' => $request->salle_de_classe_id,
        ]);

        // Mettre à jour les horaires
        $emploi->ref_horaires()->sync($request->horaire_ids);

        // Envoyer notification aux étudiants de la classe
        NotificationService::notifyScheduleUpdated($emploi);

        return response()->json([
            'success' => true,
            'message' => 'Séance modifiée avec succès.'
        ]);
    }

    /**
     * Supprimer une séance via le calendrier
     */
    public function deleteCalendarEvent($id)
    {
        $emploi = EmploiTemps::with(['subject', 'jour', 'ref_horaires'])->find($id);
        if (!$emploi) {
            return response()->json(['success' => false, 'message' => 'Séance non trouvée.'], 404);
        }

        // Sauvegarder les informations avant suppression pour la notification
        $classId = $emploi->class_id;
        $subjectName = $emploi->subject->name ?? 'Matière';
        $jourName = $emploi->jour->libelle_fr ?? 'Jour';
        $horaires = $emploi->ref_horaires->pluck('libelle_fr')->implode(', ');

        // Supprimer les horaires associés
        EmploiHoraire::where('emploi_temps_id', $id)->delete();

        // Supprimer la séance
        $emploi->delete();

        // Envoyer notification aux étudiants de la classe
        NotificationService::notifyScheduleDeleted($classId, $subjectName, $jourName, $horaires);

        return response()->json([
            'success' => true,
            'message' => 'Séance supprimée avec succès.'
        ]);
    }

    /**
     * Vérifier les conflits d'emploi du temps
     */
    private function checkConflicts(Request $request, $anneeId, $excludeId = null)
    {
        $errors = [];
        $teacherId = $request->teacher_id;
        $classId = $request->class_id;
        $jourId = $request->jour_id;
        $trimesterId = $request->trimester_id;
        $horaireIds = $request->horaire_ids;
        $salleId = $request->salle_de_classe_id;

        foreach ($horaireIds as $horaireId) {
            $horaire = Horaire::find($horaireId);
            $jour = Jour::find($jourId);

            // Vérifier si l'enseignant a déjà un cours à cette heure
            $conflictQuery = EmploiTemps::where('teacher_id', $teacherId)
                ->where('jour_id', $jourId)
                ->where('trimester_id', $trimesterId)
                ->where('annee_id', $anneeId)
                ->whereHas('horairess', function($query) use ($horaireId) {
                    $query->where('horaire_id', $horaireId);
                });

            if ($excludeId) {
                $conflictQuery->where('id', '!=', $excludeId);
            }

            if ($conflictQuery->exists()) {
                $teacher = Teacher::find($teacherId);
                $conflictingEmploi = $conflictQuery->with(['classe', 'subject'])->first();
                $errors[] = [
                    'type' => 'teacher',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'message' => "L'enseignant <strong>{$teacher->name}</strong> a déjà un cours",
                    'details' => "Classe: {$conflictingEmploi->classe->nom} - Matière: {$conflictingEmploi->subject->name}",
                    'time' => "{$jour->libelle_fr} à {$horaire->libelle_fr}"
                ];
            }

            // Vérifier si la classe a déjà un cours à cette heure
            $conflictClassQuery = EmploiTemps::where('class_id', $classId)
                ->where('jour_id', $jourId)
                ->where('trimester_id', $trimesterId)
                ->where('annee_id', $anneeId)
                ->whereHas('horairess', function($query) use ($horaireId) {
                    $query->where('horaire_id', $horaireId);
                });

            if ($excludeId) {
                $conflictClassQuery->where('id', '!=', $excludeId);
            }

            if ($conflictClassQuery->exists()) {
                $classe = Classe::find($classId);
                $conflictingEmploi = $conflictClassQuery->with(['teacher', 'subject'])->first();
                $errors[] = [
                    'type' => 'class',
                    'icon' => 'fas fa-users',
                    'message' => "La classe <strong>{$classe->nom}</strong> a déjà un cours",
                    'details' => "Prof: {$conflictingEmploi->teacher->name} - Matière: {$conflictingEmploi->subject->name}",
                    'time' => "{$jour->libelle_fr} à {$horaire->libelle_fr}"
                ];
            }

            // Vérifier si la salle est déjà occupée à cette heure
            if ($salleId) {
                $conflictSalleQuery = EmploiTemps::where('salle_de_classe_id', $salleId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    });

                if ($excludeId) {
                    $conflictSalleQuery->where('id', '!=', $excludeId);
                }

                if ($conflictSalleQuery->exists()) {
                    $salle = \App\Models\SalleDeClasse::find($salleId);
                    $conflictingEmploi = $conflictSalleQuery->with(['classe', 'teacher', 'subject'])->first();
                    $errors[] = [
                        'type' => 'room',
                        'icon' => 'fas fa-door-open',
                        'message' => "La salle <strong>{$salle->name}</strong> est déjà occupée",
                        'details' => "Classe: {$conflictingEmploi->classe->nom} - Prof: {$conflictingEmploi->teacher->name}",
                        'time' => "{$jour->libelle_fr} à {$horaire->libelle_fr}"
                    ];
                }
            }
        }

        // Vérifier le dépassement des heures hebdomadaires
        $hoursWarning = $this->checkWeeklyHoursLimit($teacherId, $classId, $trimesterId, $anneeId, $horaireIds, $excludeId);
        if ($hoursWarning) {
            $errors[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'message' => $hoursWarning,
                'details' => '',
                'time' => ''
            ];
        }

        return $errors;
    }

    /**
     * Vérifier si l'ajout de cette séance dépasse les heures hebdomadaires assignées
     */
    private function checkWeeklyHoursLimit($teacherId, $classId, $trimesterId, $anneeId, $horaireIds, $excludeId = null)
    {
        // Récupérer l'affectation enseignant-classe-trimestre
        $assignment = SubjectTeacher::where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->first();

        if (!$assignment || !$assignment->heures_semaine) {
            return null; // Pas de limite définie
        }

        // Calculer les heures actuelles
        $currentHours = $assignment->heures_reelles;

        // Calculer les nouvelles heures à ajouter
        $newMinutes = 0;
        foreach ($horaireIds as $horaireId) {
            $horaire = Horaire::find($horaireId);
            if ($horaire && $horaire->start_time && $horaire->end_time) {
                $start = Carbon::parse($horaire->start_time);
                $end = Carbon::parse($horaire->end_time);
                $newMinutes += $end->diffInMinutes($start);
            }
        }
        $newHours = $newMinutes / 60;

        // Si c'est une mise à jour, soustraire les heures de la séance existante
        if ($excludeId) {
            $existingEmploi = EmploiTemps::with('ref_horaires')->find($excludeId);
            if ($existingEmploi) {
                $existingMinutes = 0;
                foreach ($existingEmploi->ref_horaires as $horaire) {
                    if ($horaire->start_time && $horaire->end_time) {
                        $start = Carbon::parse($horaire->start_time);
                        $end = Carbon::parse($horaire->end_time);
                        $existingMinutes += $end->diffInMinutes($start);
                    }
                }
                $currentHours -= ($existingMinutes / 60);
            }
        }

        // Vérifier le total
        $totalHours = $currentHours + $newHours;
        $limit = $assignment->heures_semaine;

        if ($totalHours > $limit) {
            $teacher = Teacher::find($teacherId);
            $classe = Classe::find($classId);
            return "Attention: L'enseignant {$teacher->name} dépassera sa limite d'heures hebdomadaires pour la classe {$classe->nom}. "
                . "Limite: {$limit}h, Total après ajout: " . round($totalHours, 1) . "h";
        }

        return null;
    }

    /**
     * Vérifier la disponibilité en temps réel (API)
     */
    public function checkAvailability(Request $request)
    {
        $jourId = $request->jour_id;
        $horaireIds = $request->horaire_ids ?? [];
        $teacherId = $request->teacher_id;
        $salleId = $request->salle_de_classe_id;
        $trimesterId = $request->trimester_id;
        $excludeId = $request->exclude_id;

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        if (!$anneeId || !$jourId || empty($horaireIds)) {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        $conflicts = [];
        $jour = Jour::find($jourId);

        foreach ($horaireIds as $horaireId) {
            $horaire = Horaire::find($horaireId);

            // Vérifier l'enseignant
            if ($teacherId) {
                $teacherConflict = EmploiTemps::where('teacher_id', $teacherId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    });

                if ($excludeId) {
                    $teacherConflict->where('id', '!=', $excludeId);
                }

                if ($teacherConflict->exists()) {
                    $teacher = Teacher::find($teacherId);
                    $conflictEmploi = $teacherConflict->with(['classe', 'subject'])->first();
                    $conflicts[] = [
                        'type' => 'teacher',
                        'icon' => 'fas fa-chalkboard-teacher',
                        'title' => 'Enseignant occupé',
                        'message' => "<strong>{$teacher->name}</strong> a déjà un cours",
                        'details' => "Classe: {$conflictEmploi->classe->nom} | Matière: {$conflictEmploi->subject->name}",
                        'time' => "{$jour->libelle_fr} - {$horaire->libelle_fr}"
                    ];
                }
            }

            // Vérifier la salle
            if ($salleId) {
                $salleConflict = EmploiTemps::where('salle_de_classe_id', $salleId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    });

                if ($excludeId) {
                    $salleConflict->where('id', '!=', $excludeId);
                }

                if ($salleConflict->exists()) {
                    $salle = \App\Models\SalleDeClasse::find($salleId);
                    $conflictEmploi = $salleConflict->with(['classe', 'teacher'])->first();
                    $conflicts[] = [
                        'type' => 'room',
                        'icon' => 'fas fa-door-open',
                        'title' => 'Salle occupée',
                        'message' => "<strong>{$salle->name}</strong> est déjà réservée",
                        'details' => "Classe: {$conflictEmploi->classe->nom} | Prof: {$conflictEmploi->teacher->name}",
                        'time' => "{$jour->libelle_fr} - {$horaire->libelle_fr}"
                    ];
                }
            }
        }

        // Vérifier la capacité de la salle par rapport à la classe
        if ($salleId && $request->class_id) {
            $salle = \App\Models\SalleDeClasse::find($salleId);
            $classe = Classe::find($request->class_id);
            $classCapacity = $classe->capacity ?? 20;

            if ($salle && $salle->capacity < $classCapacity) {
                $conflicts[] = [
                    'type' => 'capacity',
                    'icon' => 'fas fa-users',
                    'title' => 'Capacité insuffisante',
                    'message' => "<strong>{$salle->name}</strong> a une capacité insuffisante",
                    'details' => "Salle: {$salle->capacity} places | Classe: {$classCapacity} étudiants",
                    'time' => ''
                ];
            }
        }

        return response()->json([
            'available' => empty($conflicts),
            'conflicts' => $conflicts
        ]);
    }

    public function getTrimesters(Request $request)
    {
        $classId = $request->class_id;


        if (!$classId) {
            return response()->json(['trimesters' => []]);
        }
        try {
            $classe = Classe::findOrFail($classId);
            $trimesters = $classe->niveau->trimesters;
            return response()->json(['trimesters' => $trimesters]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading trimesters', 'trimesters' => []], 500);
        }
    }

    public function create()
    {
        // Get only classes from active school year
        $anneeActive = \App\Models\Anneescolaire::where('is_active', true)->first();
        $classes = $anneeActive
            ? \App\Models\Classe::where('annee_id', $anneeActive->id)->orderBy('nom')->get()
            : collect();

        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        $trimesters = \App\Models\Trimester::all();
        $jours = \App\Models\Jour::all();
        $horaires = \App\Models\Horaire::all();
        $salles = \App\Models\SalleDeClasse::all();
        return view('admin.emplois.create', compact('classes', 'subjects', 'teachers', 'trimesters', 'jours', 'horaires', 'salles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|array',
            'subject_id.*' => 'required|exists:subjects,id',
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'required|exists:teachers,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'jour_id' => 'required|array',
            'jour_id.*' => 'required|exists:sct_refjours,id',
            'horaire_id' => 'required|array',
            'horaire_id.*' => 'required|array',
            'horaire_id.*.*' => 'required|exists:sct_ref_horaires,id',
            'salle_de_classe_id' => 'nullable|array',
            'salle_de_classe_id.*' => 'nullable|exists:salle_de_classes,id',
        ], [
            'class_id.required' => 'La classe est obligatoire.',
            'class_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'subject_id.required' => 'Au moins une matière est obligatoire.',
            'subject_id.*.exists' => 'Une des matières sélectionnées n\'existe pas.',
            'teacher_id.required' => 'Au moins un professeur est obligatoire.',
            'teacher_id.*.exists' => 'Un des professeurs sélectionnés n\'existe pas.',
            'trimester_id.required' => 'Le trimestre est obligatoire.',
            'trimester_id.exists' => 'Le trimestre sélectionné n\'existe pas.',
            'jour_id.required' => 'Au moins un jour est obligatoire.',
            'jour_id.*.exists' => 'Un des jours sélectionnés n\'existe pas.',
            'horaire_id.required' => 'Au moins un horaire est obligatoire.',
            'horaire_id.*.*.exists' => 'Un des horaires sélectionnés n\'existe pas.',
        ]);

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        if (!$anneeId) {
            return redirect()->back()->withErrors(['error' => 'Aucune année scolaire active trouvée.']);
        }

        // Vérification de la capacité de la salle par rapport à la classe
        $classe = Classe::find($request->class_id);
        $classCapacity = $classe->capacity ?? 20;

        for ($i = 0; $i < count($request->teacher_id); $i++) {
            $salleId = $request->salle_de_classe_id[$i] ?? null;
            if ($salleId) {
                $salle = \App\Models\SalleDeClasse::find($salleId);
                if ($salle && $salle->capacity < $classCapacity) {
                    return redirect()->back()->withErrors([
                        "La capacité de la salle {$salle->name} ({$salle->capacity} places) est inférieure à celle de la classe ({$classCapacity} étudiants)."
                    ])->withInput();
                }
            }
        }

        // فاليديشن متقدم للتحقق من التضارب
        $errors = [];

        for ($i = 0; $i < count($request->teacher_id); $i++) {
            $teacherId = $request->teacher_id[$i];
            $subjectId = $request->subject_id[$i];
            $jourId = $request->jour_id[$i];
            $horaireIds = $request->horaire_id[$i]; // مصفوفة من الحصص الزمنية
            $classId = $request->class_id;
            $trimesterId = $request->trimester_id;

            // فحص كل حصة زمنية للتضارب
            foreach ($horaireIds as $horaireId) {
                // التحقق من عدم تكرار الأستاذ في نفس اليوم والوقت مع نفس المادة
                $conflictSameTeacherSubject = EmploiTemps::where('teacher_id', $teacherId)
                    ->where('subject_id', $subjectId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictSameTeacherSubject) {
                    $teacher = Teacher::find($teacherId);
                    $subject = Subject::find($subjectId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "Le professeur {$teacher->name} enseigne déjà la matière {$subject->name} le {$jour->libelle_fr} à {$horaire->libelle_fr}.";
                }

                // التحقق من عدم وجود أستاذين يدرسان نفس المادة في نفس القسم والتوقيت
                $conflictSameSubjectTime = EmploiTemps::where('class_id', $classId)
                    ->where('subject_id', $subjectId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->where('teacher_id', '!=', $teacherId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictSameSubjectTime) {
                    $subject = Subject::find($subjectId);
                    $classe = Classe::find($classId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "La matière {$subject->name} est déjà programmée pour la classe {$classe->nom} le {$jour->libelle_fr} à {$horaire->libelle_fr} avec un autre professeur.";
                }

                // التحقق من عدم تكرار الأستاذ في نفس الوقت (حتى لو مادة مختلفة)
                $conflictTeacherTime = EmploiTemps::where('teacher_id', $teacherId)
                    ->where('jour_id', $jourId)
                    ->where('trimester_id', $trimesterId)
                    ->where('annee_id', $anneeId)
                    ->whereHas('horairess', function($query) use ($horaireId) {
                        $query->where('horaire_id', $horaireId);
                    })
                    ->exists();

                if ($conflictTeacherTime) {
                    $teacher = Teacher::find($teacherId);
                    $jour = Jour::find($jourId);
                    $horaire = Horaire::find($horaireId);

                    $errors[] = "Le professeur {$teacher->name} a déjà un cours programmé le {$jour->libelle_fr} à {$horaire->libelle_fr}.";
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // إنشاء الحصص إذا لم توجد تضارب
        for ($i = 0; $i < count($request->teacher_id); $i++) {
            $emploi = EmploiTemps::create([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id[$i],
                'teacher_id' => $request->teacher_id[$i],
                'trimester_id' => $request->trimester_id,
                'annee_id' => $anneeId,
                'jour_id' => $request->jour_id[$i],
                'salle_de_classe_id' => $request->salle_de_classe_id[$i] ?? null,
            ]);

            // إضافة جميع الحصص الزمنية المحددة
            foreach ($request->horaire_id[$i] as $horaireId) {
                EmploiHoraire::create([
                    'emploi_temps_id' => $emploi->id,
                    'horaire_id' => $horaireId,
                ]);
            }

            // إرسال إشعار للطلاب
            NotificationService::notifyScheduleCreated($emploi);
        }

        return redirect()->route('web.emplois.index')->with('success', 'Emploi du temps ajouté avec succès.');
    }

    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = EmploiTemps::with(['classe', 'subject', 'teacher', 'jour', 'horairess', 'trimester', 'annee', 'salle']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('teacher', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('subject', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('classe', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $total = $query->count();

        $emplois = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($emplois as $emploi) {
            // إعادة تعيين المتغير لكل حصة
            $horaires = '';

            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $emploi->id . '" title="Modifier"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $emploi->id . '" title="Supprimer"><i class="fa fa-trash"></i></a>';

            // تجميع الحصص لهذا الدرس
            foreach ($emploi->horairess as $horaire) {
                $horaires .= '<span class="badge badge-primary me-1">' . ($horaire->libelle_fr ?? $horaire->libelle_ar ?? '-') . '</span>';
            }

            // إذا لم توجد حصص، عرض رسالة
            if (empty($horaires)) {
                $horaires = '<span class="text-muted">-</span>';
            }

            $rows[] = [
                'id' => $emploi->id,
                'no' => $no++,
                'class' => $emploi->classe?->nom ?? '-',
                'subject' => $emploi->subject?->name ?? '-',
                'teacher' => $emploi->teacher?->name ?? '-',
                'jour' => $emploi->jour?->libelle_fr ?? $emploi->jour?->libelle_ar ?? '-',
                'horaire' => $horaires,
                'salle' => $emploi->salle?->name ?? '<span class="text-muted">Non assignée</span>',
                'trimester' => $emploi->trimester?->name ?? '-',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }


    public function showEmploi($classId)
    {
        $classe = Classe::findOrFail($classId);
        $emplois_temps =EmploiTemps::with('salle')->where('class_id', $classe->id)->get();
        $calendarData = $this->generateDataCalendar(Jour::orderBy('ordre')->get(), $emplois_temps);
//dd($calendarData);

        ob_start();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetAuthor('Emploi du temps');
        $mpdf->SetTitle('Emploi du temps ');
        $mpdf->SetSubject('Emploi du temps');
        $mpdf->SetFont('arial', '', 12);
        $mpdf->writeHTML(view('admin.sct_emplois_temps.pdf.classe_emplois_pdf', [
            'classe' => $classe,
            'calendarData' => $calendarData,
            'sctHoraires' =>Horaire::get()->sortBy('ordre'),
            'uniqueJours' => Jour::orderBy('ordre')->get(),
            'date_ref' => '',
        ])->render());
        $mpdf->SetHTMLFooter('
                <table>
                    <tr>
                        <td  align="center" >Imprimé le </td>
                        <td >{DATE j-m-Y H:m:s}</td>
                    </tr>
                </table>'
        );
        $mpdf->Output();
        ob_end_flush();

    }


    public function edit(EmploiTemps $emploi)
    {
        // Get only classes from active school year
        $anneeActive = \App\Models\Anneescolaire::where('is_active', true)->first();
        $classes = $anneeActive
            ? \App\Models\Classe::where('annee_id', $anneeActive->id)->orderBy('nom')->get()
            : collect();
        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        $trimesters = \App\Models\Trimester::all();
        $anneescolaires = \App\Models\Anneescolaire::all();
        $jours = \App\Models\Jour::all();
        $horaires = \App\Models\Horaire::all();

        // Load the related horaires for this emploi
        $emploi->load('ref_horaires');
        $selectedHoraires = $emploi->ref_horaires->pluck('id')->toArray();

        return view('admin.emplois.edit', compact('emploi', 'classes', 'subjects', 'teachers', 'trimesters', 'anneescolaires', 'jours', 'horaires', 'selectedHoraires'));
    }

    public function update(Request $request, EmploiTemps $emploi)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'trimester_id' => 'required',
            'annee_id' => 'required',
            'jour_id' => 'required',
            'horaires' => 'required|array',
            'horaires.*' => 'exists:sct_ref_horaires,id',
        ]);

        // Update the basic fields
        $emploi->update($request->except('horaires'));

        // Sync the horaires relationship (this will remove old ones and add new ones)
        $emploi->ref_horaires()->sync($request->horaires);

        // إرسال إشعار للطلاب بتعديل الحصة
        NotificationService::notifyScheduleUpdated($emploi);

        return redirect()->route('web.emplois.index')->with('success', 'Emploi du temps modifié avec succès.');
    }

    public function destroy(EmploiTemps $emploi)
    {
        // تحميل البيانات قبل الحذف للإشعار
        $emploi->load(['subject', 'jour', 'ref_horaires']);

        $classId = $emploi->class_id;
        $subjectName = $emploi->subject->name ?? 'Matière';
        $jourName = $emploi->jour->libelle_fr ?? 'Jour';
        $horaires = $emploi->ref_horaires->pluck('libelle_fr')->implode(', ');

        $emploi->delete();

        // إرسال إشعار للطلاب بحذف الحصة
        NotificationService::notifyScheduleDeleted($classId, $subjectName, $jourName, $horaires);

        return redirect()->route('web.emplois.index')->with('success', 'Emploi supprimé.');
    }

    public function generateDataCalendar($weekDays, $emplois)
    {
        $calendarData = [];
        $timeRange = Horaire::get()->sortBy('ordre');
        $timeRangeArray = $timeRange->values();

        // تحديد الفترات الزمنية مع استثناء فترة الراحة 14-15
        $timeSlots = [
            ['start' => '08:00', 'end' => '10:00'],
            ['start' => '10:00', 'end' => '12:00'],
            ['start' => '12:00', 'end' => '14:00'],
            ['start' => '15:00', 'end' => '17:00'], // تخطي 14-15
            ['start' => '17:00', 'end' => '19:00'],
        ];

        foreach ($timeSlots as $slot) {
            // تنسيق الوقت بصيغة 8h-10h
            $startHour = intval(substr($slot['start'], 0, 2));
            $endHour = intval(substr($slot['end'], 0, 2));
            $timeText = $startHour . 'h-' . $endHour . 'h';

            // الحصول على معرفات الحصص لهذه الفترة
            $horaireIds = $timeRangeArray->filter(function($horaire) use ($slot) {
                $start = substr($horaire->start_time, 0, 5);
                return $start >= $slot['start'] && $start < $slot['end'];
            })->pluck('id')->toArray();

            if (empty($horaireIds)) {
                continue;
            }

            $calendarData[$timeText] = [];

            foreach ($weekDays as $day) {
                $detail = $emplois->where('jour_id', $day->id)
                    ->whereIn('id',
                        EmploiHoraire::whereIn('horaire_id', $horaireIds)
                            ->pluck('emploi_temps_id')
                            ->toArray()
                    )->first();

                if ($detail) {
                    $calendarData[$timeText][] = [
                        'matiere' => $detail->subject->name,
                        'rowspan' => 1,
                        'date' => $day->libelle,
                        'id' => $detail->id,
                        'nbr_heure' => $detail->ref_horaires()->count(),
                        'emploi' => $detail,
                    ];
                } else {
                    $calendarData[$timeText][] = 1;
                }
            }
        }

        return $calendarData;
    }

    public function getTeachers(Request $request)
    {
        // إضافة debugging
        \Log::info('getTeachers called', [
            'class_id' => $request->class_id,
            'trimester_id' => $request->trimester_id,
            'all_params' => $request->all()
        ]);

        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;

        if (!$classId || !$trimesterId) {
            \Log::info('Missing parameters', ['class_id' => $classId, 'trimester_id' => $trimesterId]);
            return response()->json(['data' => []]);
        }

        try {
            $classe = Classe::findOrFail($classId);
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            if (!$anneeId) {
                return response()->json(['error' => 'No active academic year found', 'data' => []], 404);
            }

            // جلب الأساتذة المعينين لهذا القسم والفصل الدراسي والسنة
            $subjectTeachers = \App\Models\SubjectTeacher::where('trimester_id', $trimesterId)
                                                        ->where('annee_id', $anneeId)
                                                        ->where('class_id', $classId)
                                                        ->with(['teacher', 'subject'])
                                                        ->get();

            // تجميع الأساتذة والمواد
            $teachersData = [];

            foreach ($subjectTeachers as $st) {
                if (!$st->teacher) continue;

                $teacherId = $st->teacher->id;

                if (!isset($teachersData[$teacherId])) {
                    $teachersData[$teacherId] = [
                        'id' => $st->teacher->id,
                        'nom' => $st->teacher->name, // استخدام name بدلاً من nom
                        'prenom' => '', // فارغ لأن الاسم كامل في name
                        'full_name' => $st->teacher->name,
                        'subjects' => []
                    ];
                }

                if ($st->subject) {
                    $teachersData[$teacherId]['subjects'][] = [
                        'id' => $st->subject->id,
                        'name' => $st->subject->name,
                        'coefficient' => $st->subject->coefficient ?? 1
                    ];
                }
            }

            // تحويل إلى مصفوفة مفهرسة
            $formattedTeachers = array_values($teachersData);

            return response()->json(['data' => $formattedTeachers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading teachers: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function getSubjects(Request $request)
    {
        $teacherId = $request->teacher_id;
        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;

        if (!$teacherId) {
            return response()->json(['subjects' => []]);
        }

        try {
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            if (!$anneeId) {
                return response()->json(['error' => 'No active academic year found', 'subjects' => []], 404);
            }

            // جلب المواد من جدول subject_teacher مباشرة
            $subjectTeachers = \App\Models\SubjectTeacher::where('teacher_id', $teacherId)
                                                        ->where('annee_id', $anneeId)
                                                        ->where('trimester_id', $trimesterId)
                                                        ->where('class_id', $classId)
                                                        ->with('subject')
                                                        ->get();

            $subjects = [];
            foreach ($subjectTeachers as $st) {
                if ($st->subject) {
                    $subjects[] = [
                        'id' => $st->subject->id,
                        'name' => $st->subject->name,
                        'coefficient' => $st->subject->coefficient ?? 1,
                        'code' => $st->subject->code
                    ];
                }
            }

            return response()->json(['subjects' => $subjects]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading subjects: ' . $e->getMessage(), 'subjects' => []], 500);
        }
    }

    /**
     * جلب الأساتذة الذين يدرسون في قسم معين
     */
    /**
     * Récupérer les statistiques pour la page index
     */
    public function getStats()
    {
        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        if (!$anneeId) {
            return response()->json([
                'total' => 0,
                'classes' => 0,
                'teachers' => 0,
                'subjects' => 0
            ]);
        }

        $total = EmploiTemps::where('annee_id', $anneeId)->count();
        $classes = EmploiTemps::where('annee_id', $anneeId)->distinct('class_id')->count('class_id');
        $teachers = EmploiTemps::where('annee_id', $anneeId)->distinct('teacher_id')->count('teacher_id');
        $subjects = EmploiTemps::where('annee_id', $anneeId)->distinct('subject_id')->count('subject_id');

        return response()->json([
            'total' => $total,
            'classes' => $classes,
            'teachers' => $teachers,
            'subjects' => $subjects
        ]);
    }

    /**
     * Récupérer les données pour les filtres
     */
    public function getFilters()
    {
        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        $classes = Classe::where('annee_id', $anneeId)->orderBy('nom')->get(['id', 'nom']);
        $teachers = Teacher::orderBy('name')->get(['id', 'name']);
        $jours = Jour::orderBy('ordre')->get(['id', 'libelle_fr']);
        $trimesters = \App\Models\Trimester::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'classes' => $classes,
            'teachers' => $teachers,
            'jours' => $jours,
            'trimesters' => $trimesters
        ]);
    }

    public function getTeachersByDepartment(Request $request)
    {
        $specialiteId = $request->specialite_id;
        $trimesterId = $request->trimester_id;

        if (!$specialiteId) {
            return response()->json(['teachers' => []]);
        }

        try {
            $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

            $classId = $request->class_id;

            $query = Teacher::whereHas('subjects', function ($q) use ($specialiteId, $trimesterId, $anneeId, $classId) {
                $q->where('specialite_id', $specialiteId);
                if ($trimesterId) {
                    $q->whereHas('teachers', function ($subQuery) use ($trimesterId, $anneeId, $classId) {
                        $subQuery->where('trimester_id', $trimesterId);
                        if ($anneeId) {
                            $subQuery->where('annee_id', $anneeId);
                        }
                        if ($classId) {
                            $subQuery->where('class_id', $classId);
                        }
                    });
                }
            });

            $teachers = $query->with(['subjects' => function ($query) use ($specialiteId) {
                $query->where('specialite_id', $specialiteId);
            }])->get();

            $formattedTeachers = $teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'nom' => $teacher->nom,
                    'prenom' => $teacher->prenom,
                    'full_name' => $teacher->nom . ' ' . $teacher->prenom,
                    'email' => $teacher->email,
                    'subjects_count' => $teacher->subjects->count(),
                    'subjects' => $teacher->subjects->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name' => $subject->name,
                            'coefficient' => $subject->coefficient
                        ];
                    })
                ];
            });

            return response()->json(['teachers' => $formattedTeachers]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading teachers: ' . $e->getMessage(), 'teachers' => []], 500);
        }
    }

    /**
     * Export all classes schedules to a single PDF
     */
    public function exportAllClassesPdf(Request $request)
    {
        $anneeId = $request->annee_id;

        // If no year specified, use active year
        if (!$anneeId) {
            $anneeActive = Anneescolaire::where('is_active', true)->first();
            $anneeId = $anneeActive ? $anneeActive->id : null;
        }

        if (!$anneeId) {
            return redirect()->back()->with('error', 'Aucune année scolaire sélectionnée.');
        }

        $annee = Anneescolaire::find($anneeId);

        // Get all classes for this year that have schedules
        $classes = Classe::with(['niveau', 'specialite', 'annee'])
            ->where('annee_id', $anneeId)
            ->whereHas('emplois')
            ->orderBy('nom')
            ->get();

        if ($classes->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune classe avec emploi du temps trouvée pour cette année.');
        }

        $weekDays = Jour::orderBy('ordre')->get();
        $horaires = Horaire::get()->sortBy('ordre');

        ob_start();
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetAuthor('Emplois du temps');
        $mpdf->SetTitle('Emplois du temps - Toutes les classes - ' . ($annee->annee ?? ''));
        $mpdf->SetSubject('Emplois du temps');
        $mpdf->SetFont('arial', '', 12);

        $isFirstPage = true;

        foreach ($classes as $classe) {
            $emplois_temps = EmploiTemps::with(['salle', 'subject.subjectType', 'teacher'])
                ->where('class_id', $classe->id)
                ->get();

            if ($emplois_temps->isEmpty()) {
                continue;
            }

            $calendarData = $this->generateDataCalendar($weekDays, $emplois_temps);

            if (!$isFirstPage) {
                $mpdf->AddPage();
            }
            $isFirstPage = false;

            $mpdf->writeHTML(view('admin.sct_emplois_temps.pdf.classe_emplois_pdf', [
                'classe' => $classe,
                'calendarData' => $calendarData,
                'sctHoraires' => $horaires,
                'uniqueJours' => $weekDays,
                'date_ref' => '',
            ])->render());
        }

        $mpdf->SetHTMLFooter('
            <table>
                <tr>
                    <td align="center">Imprimé le </td>
                    <td>{DATE j-m-Y H:m:s}</td>
                </tr>
            </table>'
        );

        $mpdf->Output('emplois_temps_toutes_classes_' . ($annee->annee ?? '') . '.pdf', 'I');
        ob_end_flush();
    }
}

