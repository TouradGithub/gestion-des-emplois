<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\ClasseStudent;
use App\Models\NiveauPedagogique;
use App\Models\Speciality;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Get only classes from active school year
        $anneeActive = Anneescolaire::where('is_active', true)->first();
        $classes = $anneeActive
            ? Classe::with(['niveau', 'specialite', 'annee', 'emplois'])
                ->where('annee_id', $anneeActive->id)
                ->orderBy('nom')
                ->get()
            : collect();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $niveaux = NiveauPedagogique::all();
        $specialites = Speciality::all();
        $annees = Anneescolaire::all();
        return view('admin.classes.create', compact('niveaux', 'specialites', 'annees'));
    }




    public function store(Request $request)
    {
        $niveau = NiveauPedagogique::findOrFail($request->niveau_pedagogique_id);
        $speciality = Speciality::findOrFail($request->specialite_id);
        $nom = $speciality->code . '' . $niveau->ordre;

        $validator = Validator::make(array_merge($request->all(), ['nom' => $nom]), [
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
            'specialite_id' => 'required|exists:specialities,id',
            'annee_id' => 'required|exists:anneescolaires,id',
            'nom' => 'unique:classes,nom',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Classe::create([
            'nom' => $nom,
            'niveau_pedagogique_id' => $request->niveau_pedagogique_id,
            'specialite_id' => $request->specialite_id,
            'annee_id' => $request->annee_id,
        ]);

        return redirect()->route('web.classes.index')->with('success', 'Classe créée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Classe $class)
    {
        $class->load(['niveau.trimesters', 'specialite', 'annee', 'students', 'emplois.subject', 'emplois.teacher', 'emplois.jour', 'emplois.ref_horaires', 'emplois.salle', 'emplois.trimester']);

        // Get other school years for cloning emplois
        $autresAnnees = Anneescolaire::where('id', '!=', $class->annee_id)
            ->orderBy('annee', 'desc')
            ->get();

        return view('admin.classes.show', compact('class', 'autresAnnees'));
    }

    /**
     * Get emplois from another class for cloning
     */
    public function getEmploisFromClass(Request $request)
    {
        $classeId = $request->classe_id;

        $classe = Classe::with(['emplois.subject.subjectType', 'emplois.teacher', 'emplois.jour', 'emplois.ref_horaires', 'emplois.trimester', 'emplois.salle'])
            ->find($classeId);

        if (!$classe) {
            return response()->json(['emplois' => []]);
        }

        return response()->json([
            'emplois' => $classe->emplois->map(function($emploi) {
                $horaires = $emploi->ref_horaires()->orderBy('ordre')->get();
                $horaireDisplay = '-';
                if ($horaires->count() > 0) {
                    $startHour = intval(substr($horaires->first()->start_time, 0, 2));
                    $endHour = intval(substr($horaires->last()->end_time, 0, 2));
                    $horaireDisplay = $startHour . 'h-' . $endHour . 'h';
                }

                // Get subject type code (TP, TD, etc.)
                $subjectType = '';
                if ($emploi->subject && $emploi->subject->subjectType) {
                    $subjectType = $emploi->subject->subjectType->code ?? $emploi->subject->subjectType->name ?? '';
                }

                return [
                    'id' => $emploi->id,
                    'subject_name' => $emploi->subject->name ?? '-',
                    'subject_type' => $subjectType,
                    'subject_id' => $emploi->subject_id,
                    'teacher_name' => $emploi->teacher->name ?? '-',
                    'teacher_id' => $emploi->teacher_id,
                    'jour_name' => $emploi->jour->libelle_fr ?? '-',
                    'jour_id' => $emploi->jour_id,
                    'horaires' => $horaireDisplay,
                    'horaire_ids' => $emploi->ref_horaires->pluck('id')->toArray(),
                    'trimester_name' => $emploi->trimester->name ?? '-',
                    'trimester_id' => $emploi->trimester_id,
                    'salle_id' => $emploi->salle_de_classe_id,
                    'salle_name' => $emploi->salle->name ?? '-',
                ];
            })
        ]);
    }

    /**
     * Get classes from another school year with same niveau/specialite
     */
    public function getMatchingClasses(Request $request)
    {
        $anneeId = $request->annee_id;
        $currentClasseId = $request->current_classe_id;

        $currentClasse = Classe::find($currentClasseId);
        if (!$currentClasse) {
            return response()->json(['classes' => []]);
        }

        // Get classes from selected year with same niveau and specialite
        $classes = Classe::with(['niveau', 'specialite'])
            ->withCount('emplois')
            ->where('annee_id', $anneeId)
            ->where('niveau_pedagogique_id', $currentClasse->niveau_pedagogique_id)
            ->where('specialite_id', $currentClasse->specialite_id)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'classes' => $classes->map(function($classe) {
                return [
                    'id' => $classe->id,
                    'nom' => $classe->nom,
                    'niveau' => $classe->niveau->nom ?? '-',
                    'specialite' => $classe->specialite->name ?? '-',
                    'emplois_count' => $classe->emplois_count ?? 0,
                ];
            })
        ]);
    }

    /**
     * Clone emplois from another class
     */
    public function cloneEmplois(Request $request, Classe $class)
    {
        $request->validate([
            'emplois' => 'required|array|min:1',
            'emplois.*' => 'exists:emplois_temps,id',
        ], [
            'emplois.required' => 'Veuillez selectionner au moins une seance.',
            'emplois.min' => 'Veuillez selectionner au moins une seance.',
            'emplois.*.exists' => 'Une des seances selectionnees n\'existe pas.',
        ]);

        $anneeActive = Anneescolaire::where('is_active', true)->first();
        if (!$anneeActive) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune annee scolaire active trouvee.'
            ], 400);
        }

        // Check for duplicates and conflicts
        $duplicateEmplois = [];
        $conflictEmplois = [];
        $emploisToClone = [];

        foreach ($request->emplois as $emploiId) {
            $sourceEmploi = \App\Models\EmploiTemps::with('ref_horaires')->find($emploiId);
            if (!$sourceEmploi) continue;

            // Check if same emploi already exists (same subject, jour, horaires)
            $existingQuery = \App\Models\EmploiTemps::where('class_id', $class->id)
                ->where('subject_id', $sourceEmploi->subject_id)
                ->where('jour_id', $sourceEmploi->jour_id)
                ->where('trimester_id', $sourceEmploi->trimester_id)
                ->where('annee_id', $anneeActive->id);

            // Check horaire overlap
            $horaireIds = $sourceEmploi->ref_horaires->pluck('id')->toArray();
            if (count($horaireIds) > 0) {
                $existingQuery->whereHas('ref_horaires', function($q) use ($horaireIds) {
                    $q->whereIn('sct_ref_horaires.id', $horaireIds);
                });
            }

            if ($existingQuery->exists()) {
                $duplicateEmplois[] = ($sourceEmploi->subject->name ?? '-') . ' - ' . ($sourceEmploi->jour->libelle_fr ?? '-');
                continue;
            }

            // Check teacher conflict (teacher already has class at same time)
            $teacherConflictQuery = \App\Models\EmploiTemps::where('teacher_id', $sourceEmploi->teacher_id)
                ->where('jour_id', $sourceEmploi->jour_id)
                ->where('trimester_id', $sourceEmploi->trimester_id)
                ->where('annee_id', $anneeActive->id)
                ->where('class_id', '!=', $class->id);

            if (count($horaireIds) > 0) {
                $teacherConflictQuery->whereHas('ref_horaires', function($q) use ($horaireIds) {
                    $q->whereIn('sct_ref_horaires.id', $horaireIds);
                });
            }

            $teacherConflict = $teacherConflictQuery
                ->with(['classe', 'subject'])
                ->first();

            if ($teacherConflict) {
                $teacherName = $sourceEmploi->teacher->name ?? '-';
                $className = $teacherConflict->classe->nom ?? '-';
                $subjectName = $teacherConflict->subject->name ?? '-';
                $conflictEmplois[] = "L'enseignant {$teacherName} a deja une seance dans {$className} ({$subjectName}) au meme horaire";
                continue;
            }

            // Check class conflict (class already has subject at same time)
            $classConflictQuery = \App\Models\EmploiTemps::where('class_id', $class->id)
                ->where('jour_id', $sourceEmploi->jour_id)
                ->where('trimester_id', $sourceEmploi->trimester_id)
                ->where('annee_id', $anneeActive->id);

            if (count($horaireIds) > 0) {
                $classConflictQuery->whereHas('ref_horaires', function($q) use ($horaireIds) {
                    $q->whereIn('sct_ref_horaires.id', $horaireIds);
                });
            }

            $classConflict = $classConflictQuery->with('subject')->first();

            if ($classConflict) {
                $conflictEmplois[] = "La classe a deja une seance (" . ($classConflict->subject->name ?? '-') . ") au meme horaire";
                continue;
            }

            $emploisToClone[] = $sourceEmploi;
        }

        // If all selected emplois have conflicts/duplicates
        if (count($emploisToClone) === 0) {
            $message = '';
            if (count($duplicateEmplois) > 0) {
                $message .= 'Seances deja existantes: ' . implode(', ', $duplicateEmplois) . '. ';
            }
            if (count($conflictEmplois) > 0) {
                $message .= 'Conflits: ' . implode('. ', $conflictEmplois);
            }
            return response()->json([
                'success' => false,
                'message' => $message ?: 'Impossible de cloner les seances selectionnees.'
            ], 400);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $clonedCount = 0;

            foreach ($emploisToClone as $sourceEmploi) {
                $newEmploi = \App\Models\EmploiTemps::create([
                    'class_id' => $class->id,
                    'subject_id' => $sourceEmploi->subject_id,
                    'teacher_id' => $sourceEmploi->teacher_id,
                    'trimester_id' => $sourceEmploi->trimester_id,
                    'annee_id' => $anneeActive->id,
                    'jour_id' => $sourceEmploi->jour_id,
                    'salle_de_classe_id' => $sourceEmploi->salle_de_classe_id,
                ]);

                // Clone horaires
                if ($sourceEmploi->ref_horaires->count() > 0) {
                    $horaireIds = $sourceEmploi->ref_horaires->pluck('id')->toArray();
                    $newEmploi->ref_horaires()->attach($horaireIds);
                }

                // Add to subject_teacher table if not exists
                $existingSubjectTeacher = SubjectTeacher::where('teacher_id', $sourceEmploi->teacher_id)
                    ->where('subject_id', $sourceEmploi->subject_id)
                    ->where('class_id', $class->id)
                    ->where('trimester_id', $sourceEmploi->trimester_id)
                    ->where('annee_id', $anneeActive->id)
                    ->first();

                if (!$existingSubjectTeacher) {
                    // Get heures_semaine from source subject_teacher
                    $sourceSubjectTeacher = SubjectTeacher::where('teacher_id', $sourceEmploi->teacher_id)
                        ->where('subject_id', $sourceEmploi->subject_id)
                        ->where('class_id', $sourceEmploi->class_id)
                        ->where('trimester_id', $sourceEmploi->trimester_id)
                        ->where('annee_id', $sourceEmploi->annee_id)
                        ->first();

                    SubjectTeacher::create([
                        'teacher_id' => $sourceEmploi->teacher_id,
                        'subject_id' => $sourceEmploi->subject_id,
                        'class_id' => $class->id,
                        'trimester_id' => $sourceEmploi->trimester_id,
                        'annee_id' => $anneeActive->id,
                        'heures_semaine' => $sourceSubjectTeacher->heures_semaine ?? null,
                    ]);
                }

                $clonedCount++;
            }

            \Illuminate\Support\Facades\DB::commit();

            $message = $clonedCount . ' seance(s) clonee(s) avec succes';
            if (count($duplicateEmplois) > 0) {
                $message .= '. Ignorees: ' . implode(', ', $duplicateEmplois);
            }
            if (count($conflictEmplois) > 0) {
                $message .= '. Conflits: ' . count($conflictEmplois);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cloned_count' => $clonedCount,
                'skipped_duplicates' => $duplicateEmplois,
                'conflicts' => $conflictEmplois
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du clonage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $class)
    {
        $niveaux = NiveauPedagogique::all();
        $specialites = Speciality::all();
        $annees = Anneescolaire::all();
        return view('admin.classes.edit', compact('class', 'niveaux', 'specialites', 'annees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $class)
    {

        $niveau = NiveauPedagogique::findOrFail($request->niveau_pedagogique_id);
        $speciality = Speciality::findOrFail($request->specialite_id);
        $nom = $speciality->code . '' . $niveau->ordre;

        $validator = Validator::make(array_merge($request->all(), ['nom' => $nom]), [
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
            'specialite_id' => 'required|exists:specialities,id',
            'annee_id' => 'required|exists:anneescolaires,id',
            'nom' => 'unique:classes,nom,' . $class->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $class->update([
            'nom' =>  $request->nom,
            'niveau_pedagogique_id' => $request->niveau_pedagogique_id,
            'specialite_id' => $request->specialite_id,
            'annee_id' => $request->annee_id,
        ]);

        return redirect()->route('web.classes.index')->with('success', 'Classe modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $classe = Classe::findOrFail($id);

            // Verifier s'il y a des emplois lies
            if($classe->emplois()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette classe car elle est liee a des emplois du temps.'
                ], 400);
            }

            $classe->delete();

            return response()->json([
                'success' => true,
                'message' => 'Classe supprimee avec succes.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        // Filter by active school year
        $anneeActive = Anneescolaire::where('is_active', true)->first();
        $query = Classe::with(['niveau', 'specialite', 'annee']);

        if ($anneeActive) {
            $query->where('annee_id', $anneeActive->id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $classes = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($classes as $classe) {
            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary" href="' . route('web.classes.edit', $classe->id) . '"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $classe->id . '" data-url="' . route('web.classes.destroy', $classe->id) . '"><i class="fa fa-trash"></i></a>';

            $rows[] = [
                'id' => $classe->id,
                'no' => $no++,
                'nom' => $classe->nom,
                'niveau' => $classe->niveau->nom ?? 'N/A',
                'specialite' => $classe->specialite->name ?? 'N/A',
                'annee' => $classe->annee->annee ?? 'N/A',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    /**
     * Get classes from previous years with same specialite for student cloning
     */
    public function getClassesForStudents(Request $request)
    {
        $anneeId = $request->annee_id;
        $currentClasseId = $request->current_classe_id;

        $currentClasse = Classe::find($currentClasseId);
        if (!$currentClasse) {
            return response()->json(['classes' => []]);
        }

        // Get classes from selected year with same specialite only
        $classes = Classe::with(['niveau', 'specialite'])
            ->withCount('students')
            ->where('annee_id', $anneeId)
            ->where('specialite_id', $currentClasse->specialite_id)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'classes' => $classes->map(function($classe) {
                return [
                    'id' => $classe->id,
                    'nom' => $classe->nom,
                    'niveau' => $classe->niveau->nom ?? '-',
                    'specialite' => $classe->specialite->name ?? '-',
                    'students_count' => $classe->students_count ?? 0,
                ];
            })
        ]);
    }

    /**
     * Get students from a class
     */
    public function getStudentsFromClass(Request $request)
    {
        $classeId = $request->classe_id;

        $classe = Classe::with(['students' => function($q) {
            $q->orderBy('fullname');
        }])->find($classeId);

        if (!$classe) {
            return response()->json(['students' => []]);
        }

        return response()->json([
            'students' => $classe->students->map(function($student) {
                return [
                    'id' => $student->id,
                    'fullname' => $student->fullname,
                    'nni' => $student->nni,
                    'phone' => $student->phone,
                ];
            })
        ]);
    }

    /**
     * Clone students from another class
     */
    public function cloneStudents(Request $request, Classe $class)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
        ], [
            'students.required' => 'Veuillez selectionner au moins un etudiant.',
            'students.min' => 'Veuillez selectionner au moins un etudiant.',
            'students.*.exists' => 'Un des etudiants selectionnes n\'existe pas.',
        ]);

        $anneeActive = Anneescolaire::where('is_active', true)->first();
        if (!$anneeActive) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune annee scolaire active trouvee.'
            ], 400);
        }

        // Verify the class belongs to active year
        if ($class->annee_id != $anneeActive->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette classe n\'appartient pas a l\'annee scolaire active.'
            ], 400);
        }

        $duplicateStudents = [];
        $studentsToClone = [];

        foreach ($request->students as $studentId) {
            // Check if student already in this class for this year
            $exists = ClasseStudent::where('student_id', $studentId)
                ->where('annee_id', $anneeActive->id)
                ->exists();

            if ($exists) {
                $student = \App\Models\Student::find($studentId);
                $duplicateStudents[] = $student->fullname ?? 'Etudiant #' . $studentId;
                continue;
            }

            $studentsToClone[] = $studentId;
        }

        if (count($studentsToClone) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tous les etudiants selectionnes sont deja inscrits cette annee: ' . implode(', ', $duplicateStudents)
            ], 400);
        }

        DB::beginTransaction();

        try {
            $clonedCount = 0;

            foreach ($studentsToClone as $studentId) {
                // Add to classe_student history
                ClasseStudent::create([
                    'student_id' => $studentId,
                    'classe_id' => $class->id,
                    'annee_id' => $anneeActive->id,
                ]);

                // Update student's current class
                \App\Models\Student::where('id', $studentId)->update([
                    'class_id' => $class->id
                ]);

                $clonedCount++;
            }

            DB::commit();

            $message = $clonedCount . ' etudiant(s) ajoute(s) avec succes';
            if (count($duplicateStudents) > 0) {
                $message .= '. Ignores (deja inscrits): ' . implode(', ', $duplicateStudents);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cloned_count' => $clonedCount,
                'skipped_duplicates' => $duplicateStudents
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout: ' . $e->getMessage()
            ], 500);
        }
    }
}
