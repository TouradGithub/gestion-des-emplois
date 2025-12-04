<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pointage;
use App\Models\EmploiTemps;
use App\Models\Teacher;
use App\Models\Classe;
use App\Models\Trimester;
use App\Models\Anneescolaire;
use App\Models\Jour;
use App\Models\Horaire;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PointageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupération des données pour les filtres
        $teachers = Teacher::orderBy('name')->get();
        $classes = Classe::orderBy('nom')->get();

        // Construction de la requête avec filtres
        $query = Pointage::with(['emploiTemps.classe', 'emploiTemps.subject', 'teacher', 'emploiTemps.jour']);

        // Filtrage par professeur
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filtrage par date
        if ($request->filled('date_debut')) {
            $query->where('date_pointage', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_pointage', '<=', $request->date_fin);
        } elseif ($request->filled('date_debut')) {
            $query->where('date_pointage', '<=', $request->date_debut);
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Si aucune date n'est spécifiée, afficher les pointages de la semaine courante
        if (!$request->filled('date_debut') && !$request->filled('date_fin')) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('date_pointage', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
        }

        $pointages = $query->orderBy('date_pointage', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        // Statistiques rapides
        $stats = [
            'total' => $query->count(),
            'presents' => $query->clone()->where('statut', 'present')->count(),
            'absents' => $query->clone()->where('statut', 'absent')->count(),
        ];

        return view('admin.pointages.index', compact('pointages', 'teachers', 'classes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $classes = Classe::orderBy('nom')->get();
        $today = Carbon::now()->format('Y-m-d');

        return view('admin.pointages.create', compact('teachers', 'classes', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'emploi_temps_id' => 'required|exists:emplois_temps,id',
            'teacher_id' => 'required|exists:teachers,id',
            'date_pointage' => 'required|date',
            'statut' => 'required|in:present,absent',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'remarques' => 'nullable|string|max:500',
        ]);

        try {
            $pointage = Pointage::create([
                'emploi_temps_id' => $request->emploi_temps_id,
                'teacher_id' => $request->teacher_id,
                'date_pointage' => $request->date_pointage,
                'statut' => $request->statut,
                'heure_arrivee' => $request->heure_arrivee,
                'heure_depart' => $request->heure_depart,
                'remarques' => $request->remarques,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('web.pointages.index')
                           ->with('success', __('pointages.pointage_cree'));
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => __('pointages.erreur_creation')]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pointage = Pointage::with([
            'emploiTemps.classe',
            'emploiTemps.subject',
            'emploiTemps.horairess',
            'emploiTemps.teacher',
            'emploiTemps.jour'
        ])->findOrFail($id);

        // Calculer les statistiques du professeur pour ce mois
        $debut_mois = Carbon::now()->startOfMonth();
        $fin_mois = Carbon::now()->endOfMonth();

        $statsProf = [
            'total_cours_mois' => EmploiTemps::where('teacher_id', $pointage->emploiTemps->teacher_id)->count(),
            'presences_mois' => Pointage::where('teacher_id', $pointage->emploiTemps->teacher_id)
                                      ->where('statut', 'present')
                                      ->whereBetween('date_pointage', [$debut_mois, $fin_mois])
                                      ->count(),
            'absences_mois' => Pointage::where('teacher_id', $pointage->emploiTemps->teacher_id)
                                     ->where('statut', 'absent')
                                     ->whereBetween('date_pointage', [$debut_mois, $fin_mois])
                                     ->count(),
        ];

        $total_pointages_mois = $statsProf['presences_mois'] + $statsProf['absences_mois'];
        $statsProf['taux_presence'] = $total_pointages_mois > 0
            ? round(($statsProf['presences_mois'] / $total_pointages_mois) * 100)
            : 0;

        return view('admin.pointages.show', compact('pointage', 'statsProf'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pointage = Pointage::findOrFail($id);
        $teachers = Teacher::orderBy('name')->get();
        $classes = Classe::orderBy('nom')->get();

        return view('admin.pointages.edit', compact('pointage', 'teachers', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pointage = Pointage::findOrFail($id);

        $request->validate([
            'emploi_temps_id' => 'required|exists:emplois_temps,id',
            'teacher_id' => 'required|exists:teachers,id',
            'date_pointage' => 'required|date',
            'statut' => 'required|in:present,absent',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'remarques' => 'nullable|string|max:500',
        ]);

        try {
            $pointage->update([
                'emploi_temps_id' => $request->emploi_temps_id,
                'teacher_id' => $request->teacher_id,
                'date_pointage' => $request->date_pointage,
                'statut' => $request->statut,
                'heure_arrivee' => $request->heure_arrivee,
                'heure_depart' => $request->heure_depart,
                'remarques' => $request->remarques,
            ]);

            return redirect()->route('web.pointages.index')
                           ->with('success', __('pointages.pointage_modifie'));
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => __('pointages.erreur_modification')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pointage = Pointage::findOrFail($id);
            $pointage->delete();

            return redirect()->route('web.pointages.index')
                           ->with('success', __('pointages.pointage_supprime'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('pointages.erreur_suppression')]);
        }
    }

    /**
     * Obtenir les emplois du temps pour un professeur et une date donnés
     */
    public function getEmploisForTeacher(Request $request)
    {
        $teacherId = $request->teacher_id;
        $date = $request->date;

        if (!$teacherId || !$date) {
            return response()->json(['emplois' => []]);
        }

        // Obtenir le jour de la semaine
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek; // Convertir dimanche (0) en 7

        $emplois = EmploiTemps::with(['classe', 'subject', 'jour', 'horairess'])
                             ->where('teacher_id', $teacherId)
                             ->whereHas('jour', function($query) use ($dayOfWeek) {
                                 $query->where('ordre', $dayOfWeek);
                             })
                             ->get();

        return response()->json([
            'emplois' => $emplois->map(function($emploi) {
                return [
                    'id' => $emploi->id,
                    'classe_nom' => $emploi->classe->nom ?? '',
                    'subject_nom' => $emploi->subject->name ?? '',
                    'jour_nom' => $emploi->jour->nom ?? '',
                    'horaires' => $emploi->horairess->pluck('libelle_fr')->join(', '),
                    'display' => $emploi->classe->nom . ' - ' . $emploi->subject->name . ' (' . $emploi->horairess->pluck('libelle_fr')->join(', ') . ')'
                ];
            })
        ]);
    }

    /**
     * Pointage rapide pour aujourd'hui
     */
    public function pointageRapide()
    {
        $today = Carbon::now()->format('Y-m-d');
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek;

        // Obtenir tous les emplois du temps pour aujourd'hui
        $emploisAujourdhui = EmploiTemps::with(['classe', 'subject', 'teacher', 'jour', 'horairess'])
                                       ->whereHas('jour', function($query) use ($dayOfWeek) {
                                           $query->where('ordre', $dayOfWeek);
                                       })
                                       ->orderBy('teacher_id')
                                       ->get();

        // Obtenir les pointages déjà enregistrés pour aujourd'hui
        $pointagesExistants = Pointage::where('date_pointage', $today)
                                    ->pluck('emploi_temps_id')
                                    ->toArray();

        return view('admin.pointages.rapide', compact('emploisAujourdhui', 'pointagesExistants', 'today'));
    }

    /**
     * دالة مختصرة للوصول إلى الحضور السريع
     */
    public function rapide()
    {
        return $this->pointageRapide();
    }

    /**
     * Enregistrer un pointage rapide
     */
    public function storeRapide(Request $request)
    {
        $request->validate([
            'pointages' => 'required|array',
            'pointages.*.emploi_temps_id' => 'required|exists:emplois_temps,id',
            'pointages.*.statut' => 'required|in:present,absent',
            'date_pointage' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->pointages as $pointageData) {
                // Obtenir l'emploi du temps pour récupérer le teacher_id
                $emploiTemps = EmploiTemps::findOrFail($pointageData['emploi_temps_id']);

                Pointage::updateOrCreate(
                    [
                        'emploi_temps_id' => $pointageData['emploi_temps_id'],
                        'date_pointage' => $request->date_pointage,
                    ],
                    [
                        'teacher_id' => $emploiTemps->teacher_id,
                        'statut' => $pointageData['statut'],
                        'created_by' => auth()->id(),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('web.pointages.index')
                           ->with('success', __('pointages.pointage_cree'));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => __('pointages.erreur_creation')]);
        }
    }

    /**
     * Afficher le calendrier des pointages
     */
    public function calendar()
    {
        $anneeActive = Anneescolaire::where('is_active', true)->first();
        if (!$anneeActive) {
            return redirect()->back()->with('error', 'Aucune année scolaire active trouvée.');
        }

        $classes = Classe::where('annee_id', $anneeActive->id)->orderBy('nom')->get();
        $start_time = Carbon::createFromTimeString('08:00:00')->format('H:i:s');
        $end_time = Carbon::createFromTimeString('19:00:00')->format('H:i:s');

        return view('admin.pointages.calendar', compact('classes', 'start_time', 'end_time'));
    }

    /**
     * Récupérer les événements du calendrier pour les pointages
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

        $emplois = EmploiTemps::with(['subject', 'teacher', 'jour', 'ref_horaires', 'classe'])
            ->where('class_id', $classId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->get();

        $events = [];

        foreach ($emplois as $emploi) {
            $horairesList = $emploi->ref_horaires()->orderBy('ordre')->get();
            if ($horairesList->isEmpty()) {
                continue;
            }

            $startTime = $horairesList->first()->start_time;
            $endTime = $horairesList->last()->end_time;
            $horairesText = $horairesList->pluck('libelle_fr')->join(', ');

            $jourOrdre = $emploi->jour->ordre ?? 1;
            $dayMapping = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 0];
            $dayOfWeek = $dayMapping[$jourOrdre] ?? 1;

            $startDate = Carbon::parse($start);
            while ($startDate->dayOfWeek != $dayOfWeek) {
                $startDate->addDay();
            }

            $eventDate = $startDate->format('Y-m-d');
            $eventStart = $eventDate . 'T' . $startTime;
            $eventEnd = $eventDate . 'T' . $endTime;

            // Vérifier si un pointage existe pour cette séance et cette date
            $pointage = Pointage::where('emploi_temps_id', $emploi->id)
                ->where('date_pointage', $eventDate)
                ->first();

            $statut = $pointage ? $pointage->statut : null;
            $pointageId = $pointage ? $pointage->id : null;

            $title = $emploi->subject->name ?? 'Matière';
            $prof = $emploi->teacher->name ?? 'Enseignant';
            $classe = $emploi->classe->nom ?? '-';

            // Ajouter un indicateur de statut au titre
            $statutIcon = '';
            if ($statut === 'present') {
                $statutIcon = '✓ ';
            } elseif ($statut === 'absent') {
                $statutIcon = '✗ ';
            } else {
                $statutIcon = '○ ';
            }

            $events[] = [
                'id' => $emploi->id,
                'title' => $statutIcon . $title . "\n" . $prof,
                'start' => $eventStart,
                'end' => $eventEnd,
                'extendedProps' => [
                    'statut' => $statut,
                    'pointage_id' => $pointageId,
                    'teacher' => $prof,
                    'subject' => $title,
                    'classe' => $classe,
                    'horaire' => $horairesText,
                    'teacher_id' => $emploi->teacher_id
                ]
            ];
        }

        return response()->json(['events' => $events]);
    }

    /**
     * Enregistrer ou mettre à jour un pointage via le calendrier
     */
    public function storeCalendarPointage(Request $request)
    {
        $request->validate([
            'emploi_temps_id' => 'required|exists:emplois_temps,id',
            'date_pointage' => 'required|date',
            'statut' => 'required|in:present,absent',
            'remarques' => 'nullable|string|max:500',
        ]);

        try {
            $emploiTemps = EmploiTemps::findOrFail($request->emploi_temps_id);

            $pointage = Pointage::updateOrCreate(
                [
                    'emploi_temps_id' => $request->emploi_temps_id,
                    'date_pointage' => $request->date_pointage,
                ],
                [
                    'teacher_id' => $emploiTemps->teacher_id,
                    'statut' => $request->statut,
                    'remarques' => $request->remarques,
                    'heure_arrivee' => $request->statut === 'present' ? Carbon::now()->format('H:i') : null,
                    'created_by' => auth()->id(),
                ]
            );

            $message = $request->pointage_id ? 'Pointage modifié avec succès.' : 'Pointage enregistré avec succès.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'pointage_id' => $pointage->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du pointage.'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques pour le calendrier
     */
    public function getStatistiques(Request $request)
    {
        $classId = $request->class_id;
        $trimesterId = $request->trimester_id;
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        if (!$classId || !$trimesterId) {
            return response()->json(['statistiques' => []]);
        }

        $anneeId = Anneescolaire::where('is_active', true)->first()?->id;

        $emploisIds = EmploiTemps::where('class_id', $classId)
            ->where('trimester_id', $trimesterId)
            ->where('annee_id', $anneeId)
            ->pluck('id');

        $query = Pointage::whereIn('emploi_temps_id', $emploisIds);

        if ($dateDebut) {
            $query->where('date_pointage', '>=', $dateDebut);
        }
        if ($dateFin) {
            $query->where('date_pointage', '<=', $dateFin);
        }

        $stats = [
            'total' => $query->count(),
            'presents' => $query->clone()->where('statut', 'present')->count(),
            'absents' => $query->clone()->where('statut', 'absent')->count(),
        ];

        $stats['taux_presence'] = $stats['total'] > 0
            ? round(($stats['presents'] / $stats['total']) * 100, 1)
            : 0;

        return response()->json(['statistiques' => $stats]);
    }
}
