<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\ClasseStudent;
use App\Models\Niveauformation;
use App\Models\NiveauPedagogique;
use App\Models\Speciality;
use App\Models\Student;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneescolaireController extends Controller
{


    public function index()
    {
        $anneescolaires = Anneescolaire::withCount('classes')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.anneescolaires.index', compact('anneescolaires'));
    }

    public function list(Request $request)
    {
        $query = Anneescolaire::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('annee', 'like', '%' . $request->search . '%');
        }

        $total = $query->count();
        $rows = $query->offset($request->offset ?? 0)
            ->limit($request->limit ?? 10)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->get();

        $data = [];
        $index = $request->offset + 1;

        foreach ($rows as $row) {
            $operate = '<a class="btn btn-xs btn-gradient-info showdata me-1" href="' . route('web.anneescolaires.details', $row->id) . '" title="Voir"><i class="fa fa-eye"></i></a>';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata me-1" href="' . route('web.anneescolaires.edit', $row->id) . '" title="Modifier"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $row->id . '" data-url="' . route('web.anneescolaires.destroy', $row->id) . '" title="Supprimer"><i class="fa fa-trash"></i></a>';

            $data[] = [
                'id' => $row->id,
                'no' => $index++,
                'annee' => '<a href="' . route('web.anneescolaires.details', $row->id) . '" class="text-primary fw-bold">' . $row->annee . '</a>',
                'date_debut' => $row->date_debut,
                'date_fin' => $row->date_fin,
                'is_active' => $row->is_active ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>',
                'operate' => $operate
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $data,
        ]);
    }

    public function create()
    {
        return view('admin.anneescolaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'annee' => 'required|string|unique:anneescolaires,annee',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        // If this year is being activated, deactivate all other years
        if ($request->boolean('is_active')) {
            Anneescolaire::where('is_active', true)->update(['is_active' => false]);
        }

        Anneescolaire::create($request->only('annee', 'date_debut', 'date_fin', 'is_active'));

        return redirect()->route('web.anneescolaires.index')->with('success', 'Année scolaire ajoutée avec succès');
    }

    public function edit(Anneescolaire $anneescolaire)
    {
        return view('admin.anneescolaires.edit', compact('anneescolaire'));
    }

    public function update(Request $request, Anneescolaire $anneescolaire)
    {

        $request->validate([
            'annee' => 'required|string|unique:anneescolaires,annee,' . $anneescolaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        // If this year is being activated, deactivate all other years
        if ($request->boolean('is_active')) {
            Anneescolaire::where('id', '!=', $anneescolaire->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $anneescolaire->update($request->only('annee', 'date_debut', 'date_fin', 'is_active'));

        return redirect()->route('web.anneescolaires.index')->with('success', 'Année scolaire mise à jour avec succès');
    }

    public function destroy(Anneescolaire $anneescolaire)
    {
        // Check if the year has any classes
        $classesCount = Classe::where('annee_id', $anneescolaire->id)->count();

        if ($classesCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذه السنة الدراسية لأنها تحتوي على ' . $classesCount . ' قسم/أقسام. يرجى حذف الأقسام أولاً.'
            ], 400);
        }

        $anneescolaire->delete();
        return response()->json([
            'success' => true,
            'message' => 'Année scolaire supprimée avec succès'
        ]);
    }


    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Anneescolaire::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $anneescolaires = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($anneescolaires as $anneescolaire) {
            $operate = '<a class="btn btn-xs btn-gradient-info showdata me-1" href="' . route('web.anneescolaires.details', $anneescolaire->id) . '" title="Voir"><i class="fa fa-eye"></i></a>';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata me-1" data-id="' . $anneescolaire->id . '" title="Modifier"><i class="fa fa-edit"></i></a>';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $anneescolaire->id . '" data-url="' . route('web.anneescolaires.destroy', $anneescolaire->id) . '" title="Supprimer"><i class="fa fa-trash"></i></a>';

            $isActive = $anneescolaire->is_active ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
            $rows[] = [
                'id' => $anneescolaire->id,
                'no' => $no++,
                'annee' => '<a href="' . route('web.anneescolaires.details', $anneescolaire->id) . '" class="text-primary fw-bold">' . $anneescolaire->annee . '</a>',
                'date_debut' => $anneescolaire->date_debut,
                'date_fin' => $anneescolaire->date_fin,
                'is_active' => $isActive,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    /**
     * Display the specified school year with its classes.
     */
    public function showDetails(Anneescolaire $anneescolaire)
    {
        $anneescolaire->load(['classes.niveau', 'classes.specialite', 'classes.students']);

        // Get all other school years for cloning
        $autresAnnees = Anneescolaire::where('id', '!=', $anneescolaire->id)
            ->orderBy('annee', 'desc')
            ->get();

        // Get niveaux and specialites for create class modal
        $niveaux = NiveauPedagogique::all();
        $specialites = Speciality::all();

        return view('admin.anneescolaires.show', compact('anneescolaire', 'autresAnnees', 'niveaux', 'specialites'));
    }

    /**
     * Get classes from another school year for cloning.
     */
    public function getClassesFromYear(Request $request)
    {
        $anneeId = $request->annee_id;

        $classes = Classe::with(['niveau', 'specialite'])
            ->withCount('emplois')
            ->where('annee_id', $anneeId)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'classes' => $classes->map(function($classe) {
                return [
                    'id' => $classe->id,
                    'nom' => $classe->nom,
                    'niveau' => $classe->niveau->nom ?? '-',
                    'specialite' => $classe->specialite->name ?? '-',
                    'niveau_pedagogique_id' => $classe->niveau_pedagogique_id,
                    'specialite_id' => $classe->specialite_id,
                    'emplois_count' => $classe->emplois_count ?? 0,
                ];
            })
        ]);
    }

    /**
     * Store a new class for the school year.
     */
    public function storeClasse(Request $request, Anneescolaire $anneescolaire)
    {
        $request->validate([
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
            'specialite_id' => 'required|exists:specialities,id',
        ]);

        try {
            $niveau = NiveauPedagogique::findOrFail($request->niveau_pedagogique_id);
            $specialite = Speciality::findOrFail($request->specialite_id);

            // Generate class name from specialite code and niveau ordre
            $nom = $specialite->code . $niveau->ordre;

            // Check if class already exists
            $exists = Classe::where('annee_id', $anneescolaire->id)
                ->where('nom', $nom)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une classe avec ce nom existe deja pour cette annee'
                ], 400);
            }

            Classe::create([
                'nom' => $nom,
                'niveau_pedagogique_id' => $request->niveau_pedagogique_id,
                'specialite_id' => $request->specialite_id,
                'annee_id' => $anneescolaire->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Classe "' . $nom . '" creee avec succes'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la creation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clone classes from another school year.
     */
    public function cloneClasses(Request $request, Anneescolaire $anneescolaire)
    {
        $request->validate([
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:classes,id',
        ]);

        $avecEmploi = $request->boolean('avec_emploi', false);

        // First, check for duplicates and collect messages
        $duplicateClasses = [];
        $classesToClone = [];

        foreach ($request->classes as $classeId) {
            $sourceClasse = Classe::with('emplois.ref_horaires')->find($classeId);
            if ($sourceClasse) {
                $exists = Classe::where('annee_id', $anneescolaire->id)
                    ->where('nom', $sourceClasse->nom)
                    ->exists();

                if ($exists) {
                    $duplicateClasses[] = $sourceClasse->nom;
                } else {
                    $classesToClone[] = $sourceClasse;
                }
            }
        }

        // If all selected classes already exist, return error
        if (count($classesToClone) === 0 && count($duplicateClasses) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'الأقسام المحددة موجودة مسبقاً في هذه السنة: ' . implode(', ', $duplicateClasses)
            ], 400);
        }

        DB::beginTransaction();

        try {
            $classesCloned = 0;
            $emploisCloned = 0;

            foreach ($classesToClone as $sourceClasse) {
                // Create the new class
                $newClasse = Classe::create([
                    'nom' => $sourceClasse->nom,
                    'niveau_pedagogique_id' => $sourceClasse->niveau_pedagogique_id,
                    'specialite_id' => $sourceClasse->specialite_id,
                    'annee_id' => $anneescolaire->id,
                ]);
                $classesCloned++;

                // Clone emplois du temps if requested
                if ($avecEmploi && $sourceClasse->emplois->count() > 0) {
                    foreach ($sourceClasse->emplois as $emploi) {
                        // Create the new emploi temps
                        $newEmploi = \App\Models\EmploiTemps::create([
                            'class_id' => $newClasse->id,
                            'subject_id' => $emploi->subject_id,
                            'teacher_id' => $emploi->teacher_id,
                            'trimester_id' => $emploi->trimester_id,
                            'annee_id' => $anneescolaire->id,
                            'jour_id' => $emploi->jour_id,
                            'salle_de_classe_id' => $emploi->salle_de_classe_id,
                        ]);

                        // Clone the horaires relationship
                        if ($emploi->ref_horaires->count() > 0) {
                            $horaireIds = $emploi->ref_horaires->pluck('id')->toArray();
                            $newEmploi->ref_horaires()->attach($horaireIds);
                        }

                        // Add to subject_teacher table if not exists
                        $existingSubjectTeacher = SubjectTeacher::where('teacher_id', $emploi->teacher_id)
                            ->where('subject_id', $emploi->subject_id)
                            ->where('class_id', $newClasse->id)
                            ->where('trimester_id', $emploi->trimester_id)
                            ->where('annee_id', $anneescolaire->id)
                            ->first();

                        if (!$existingSubjectTeacher) {
                            // Get heures_semaine from source subject_teacher
                            $sourceSubjectTeacher = SubjectTeacher::where('teacher_id', $emploi->teacher_id)
                                ->where('subject_id', $emploi->subject_id)
                                ->where('class_id', $sourceClasse->id)
                                ->where('trimester_id', $emploi->trimester_id)
                                ->where('annee_id', $emploi->annee_id)
                                ->first();

                            SubjectTeacher::create([
                                'teacher_id' => $emploi->teacher_id,
                                'subject_id' => $emploi->subject_id,
                                'class_id' => $newClasse->id,
                                'trimester_id' => $emploi->trimester_id,
                                'annee_id' => $anneescolaire->id,
                                'heures_semaine' => $sourceSubjectTeacher->heures_semaine ?? null,
                            ]);
                        }

                        $emploisCloned++;
                    }
                }
            }

            DB::commit();

            $message = $classesCloned . ' classe(s) clonee(s) avec succes';
            if ($avecEmploi && $emploisCloned > 0) {
                $message .= ' avec ' . $emploisCloned . ' séance(s) du temps';
            }

            // Add warning about skipped duplicates
            if (count($duplicateClasses) > 0) {
                $message .= '. تم تخطي الأقسام الموجودة مسبقاً: ' . implode(', ', $duplicateClasses);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cloned_count' => $classesCloned,
                'emplois_count' => $emploisCloned,
                'skipped_duplicates' => $duplicateClasses
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du clonage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes by niveau for a specific year.
     */
    public function getClassesByNiveau(Request $request)
    {
        $anneeId = $request->annee_id;
        $niveauId = $request->niveau_id;

        $classes = Classe::with(['specialite'])
            ->where('annee_id', $anneeId)
            ->where('niveau_pedagogique_id', $niveauId)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'classes' => $classes->map(function($classe) {
                return [
                    'id' => $classe->id,
                    'nom' => $classe->nom,
                    'specialite' => $classe->specialite->name ?? '-',
                    'students_count' => $classe->students->count(),
                ];
            })
        ]);
    }

    /**
     * Get available students (not assigned to any class in the current year).
     */
    public function getAvailableStudents(Request $request)
    {
        $anneeId = $request->annee_id;
        $search = $request->search;

        // Get students who are not assigned to any class in this year
        $assignedStudentIds = Student::whereHas('classe', function($q) use ($anneeId) {
            $q->where('annee_id', $anneeId);
        })->pluck('id');

        $query = Student::whereNotIn('id', $assignedStudentIds);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('nni', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('fullname')->limit(100)->get();

        return response()->json([
            'students' => $students->map(function($student) {
                return [
                    'id' => $student->id,
                    'fullname' => $student->fullname,
                    'nni' => $student->nni,
                    'phone' => $student->phone,
                    'current_class' => $student->classe ? $student->classe->nom : null,
                ];
            })
        ]);
    }

    /**
     * Assign students to a class.
     */
    public function assignStudents(Request $request, Anneescolaire $anneescolaire)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
        ]);

        $classe = Classe::findOrFail($request->classe_id);

        // Verify the class belongs to this year
        if ($classe->annee_id != $anneescolaire->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette classe n\'appartient pas a cette annee scolaire'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $assignedCount = 0;

            foreach ($request->students as $studentId) {
                $student = Student::find($studentId);

                if ($student) {
                    // Update student's current class
                    $student->update(['class_id' => $classe->id]);

                    // Add to history table
                    ClasseStudent::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'classe_id' => $classe->id,
                            'annee_id' => $anneescolaire->id,
                        ],
                        []
                    );

                    $assignedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $assignedCount . ' etudiant(s) affecte(s) a la classe "' . $classe->nom . '" avec succes',
                'assigned_count' => $assignedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'affectation: ' . $e->getMessage()
            ], 500);
        }
    }

}
