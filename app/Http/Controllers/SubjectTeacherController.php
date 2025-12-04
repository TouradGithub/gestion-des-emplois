<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Models\Trimester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectTeacherController extends Controller
{
    public function index()
    {

        return view('admin.subjects_teachers.index');
    }

    public function create()
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $trimesters = Trimester::all();
        $classes = \App\Models\Classe::all();
        return view('admin.subjects_teachers.create' , compact('subjects', 'teachers' , 'trimesters', 'classes'));

    }

    public function store(Request $request)
    {
        $annee_id = Anneescolaire::where('is_active', 1)->first()->id;

        $request->validate([
            'subject_id' => [
                'required',
                'exists:subjects,id',
                Rule::unique('subject_teacher')->where(function ($query) use ($request, $annee_id) {
                    return $query->where('subject_id', $request->subject_id)
                        ->where('teacher_id', $request->teacher_id)
                        ->where('trimester_id', $request->trimester_id)
                        ->where('class_id', $request->class_id)
                        ->where('annee_id', $annee_id);
                }),
            ],
            'teacher_id' => 'required|exists:teachers,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'class_id' => 'required|exists:classes,id',
            'heures_semaine' => 'required|numeric|min:0|max:40',
        ],
        [
            'subject_id.unique' => 'Cette combinaison matière/enseignant/trimestre/classe existe déjà.',
            'class_id.required' => 'Le champ classe est obligatoire.',
            'heures_semaine.required' => 'Le nombre d\'heures par semaine est obligatoire.',
            'heures_semaine.numeric' => 'Le nombre d\'heures doit être un nombre valide.',
            'heures_semaine.min' => 'Le nombre d\'heures ne peut pas être négatif.',
            'heures_semaine.max' => 'Le nombre d\'heures ne peut pas dépasser 40h/semaine.',
        ]);

        SubjectTeacher::create([
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'trimester_id' => $request->trimester_id,
            'class_id' => $request->class_id,
            'annee_id' => $annee_id,
            'heures_semaine' => $request->heures_semaine,
        ]);

        return redirect()->route('web.subjects_teachers.index')->with('success', 'Subject-Teacher assignment created successfully.');
    }

    public function edit($id)
    {
        $subjectTeacher = SubjectTeacher::findOrFail($id);
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $trimesters = Trimester::all();
        $classes = Classe::all();
        $annees = Anneescolaire::all();
        return view('admin.subjects_teachers.edit', compact('subjectTeacher', 'subjects', 'teachers', 'trimesters', 'classes', 'annees'));
    }

    public function show(Request $request , $id)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = SubjectTeacher::query();

//        if ($request->has('search') && !empty($request->search)) {
//            $search = $request->search;
//            $query->where(function ($q) use ($search) {
//                $q->where('name', 'like', "%$search%")
//                    ->orWhere('email', 'like', "%$search%")
//                    ->orWhere('phone', 'like', "%$search%");
//            });
//        }

        $total = $query->count();
        $teachers = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($teachers as $teacher) {
            $operate = '';
//            if (auth()->user()->can('teachers-edit')) {
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $teacher->id . '"><i class="fa fa-edit"></i></a> ';
//            }
//            if (auth()->user()->can('teachers-delete')) {
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $teacher->id . '" data-url="' . route('web.subjects_teachers.destroy', $teacher->id) . '"><i class="fa fa-trash"></i></a>';
//            }

            $rows[] = [
                'id' => $teacher->id,
                'no' => $no++,
                'subject' => $teacher->subject->name,
                'teacher' => $teacher->teacher->name,
                'trimester' => $teacher->trimester->name,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    public function update(Request $request, $id)
    {
        $subjectTeacher = SubjectTeacher::findOrFail($id);
        $annee_id = Anneescolaire::where('is_active', 1)->first()->id;

        $request->validate([
            'subject_id' => [
                'required',
                'exists:subjects,id',
                Rule::unique('subject_teacher')->where(function ($query) use ($request, $annee_id, $id) {
                    return $query->where('subject_id', $request->subject_id)
                        ->where('teacher_id', $request->teacher_id)
                        ->where('trimester_id', $request->trimester_id)
                        ->where('class_id', $request->class_id)
                        ->where('annee_id', $annee_id)
                        ->where('id', '!=', $id);
                }),
            ],
            'teacher_id' => 'required|exists:teachers,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'class_id' => 'required|exists:classes,id',
            'heures_semaine' => 'required|numeric|min:0|max:40',
        ], [
            'subject_id.unique' => 'Cette combinaison matière/enseignant/trimestre/classe existe déjà.',
            'class_id.required' => 'Le champ classe est obligatoire.',
            'heures_semaine.required' => 'Le nombre d\'heures par semaine est obligatoire.',
        ]);

        $subjectTeacher->update([
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'trimester_id' => $request->trimester_id,
            'class_id' => $request->class_id,
            'heures_semaine' => $request->heures_semaine,
        ]);

        return redirect()->route('web.subjects_teachers.index')->with('success', 'Subject-Teacher assignment updated successfully.');
    }

    public function destroy($id)
    {

        $subjectTeacher = SubjectTeacher::findOrFail($id);
        if (!$subjectTeacher) {
            return response()->json(['error' => 'Subject-Teacher assignment not found.'], 404);
        }
        $subjectTeacher->delete();

        return response()->json(['success' => 'Subject-Teacher assignment deleted successfully.']);
    }
    public function listSubjectTeacher(Request $request)
    {

        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';
        $query = SubjectTeacher::with(['subject', 'teacher', 'trimester', 'classe']);
        $total = $query->count();
        $teachers = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();
        $rows = [];
        $no = $offset + 1;
        foreach ($teachers as $teacher) {
            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $teacher->id . '"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $teacher->id . '" data-url="' . route('web.subjects_teachers.destroy', $teacher->id) . '"><i class="fa fa-trash"></i></a>';

            // Calculate hours and taux
            $heuresSemaine = $teacher->heures_semaine ?? 0;
            $heuresReelles = $teacher->heures_reelles ?? 0;
            $taux = $teacher->taux ?? 0;

            // Taux badge
            $tauxBadge = '';
            if ($heuresSemaine > 0) {
                if ($taux < 50) {
                    $tauxBadge = '<span class="badge" style="background:#fff;color:#1a1a1a;border:1px solid #1a1a1a;">' . $taux . '%</span>';
                } elseif ($taux < 100) {
                    $tauxBadge = '<span class="badge" style="background:#666;color:#fff;">' . $taux . '%</span>';
                } elseif ($taux == 100) {
                    $tauxBadge = '<span class="badge" style="background:#1a1a1a;color:#fff;">' . $taux . '%</span>';
                } else {
                    $tauxBadge = '<span class="badge" style="background:#333;color:#fff;">' . $taux . '% ⚠</span>';
                }
            } else {
                $tauxBadge = '<span class="badge" style="background:#eee;color:#666;">Non défini</span>';
            }

            $rows[] = [
                'id' => $teacher->id,
                'no' => $no++,
                'subject' => $teacher->subject ? $teacher->subject->name : 'N/A',
                'teacher' => $teacher->teacher ? $teacher->teacher->name : 'N/A',
                'trimester' => $teacher->trimester ? $teacher->trimester->name : 'N/A',
                'classe' => $teacher->classe ? $teacher->classe->nom : 'N/A',
                'heures_semaine' => $heuresSemaine . 'h',
                'heures_reelles' => $heuresReelles . 'h',
                'taux' => $tauxBadge,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }
}
