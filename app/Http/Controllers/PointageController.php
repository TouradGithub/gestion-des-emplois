<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pointage;
use App\Models\EmploiTemps;
use App\Models\Teacher;
use App\Models\Classe;
use App\Models\Trimester;
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
}
