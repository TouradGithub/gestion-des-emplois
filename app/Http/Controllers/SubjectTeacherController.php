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
        ],
        [
        'subject_id.unique' => 'Cette combinaison matière/enseignant/trimestre/classe existe déjà.',
        'class_id.required' => 'Le champ classe est obligatoire.',
]);

        SubjectTeacher::create([
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'trimester_id' => $request->trimester_id,
            'class_id' => $request->class_id,
            'annee_id' => $annee_id,
        ]);

        return redirect()->route('web.subjects_teachers.index')->with('success', 'Subject-Teacher assignment created successfully.');
    }

    public function edit($id)
    {
        // Logic to show form for editing an existing subject-teacher assignment
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
        // Logic to update an existing subject-teacher assignment
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
            $rows[] = [
                'id' => $teacher->id,
                'no' => $no++,
                'subject' => $teacher->subject ? $teacher->subject->name : 'N/A',
                'teacher' => $teacher->teacher ? $teacher->teacher->name : 'N/A',
                'trimester' => $teacher->trimester ? $teacher->trimester->name : 'N/A',
                'classe' => $teacher->classe ? $teacher->classe->nom : 'N/A',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }
}
