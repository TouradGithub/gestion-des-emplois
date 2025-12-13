<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use App\Models\Subject;
use App\Models\SubjectType;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::with(['specialite', 'subjectType'])->latest()->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $specialites = Speciality::all();
        $subjectTypes = SubjectType::orderBy('order')->get();
        return view('admin.subjects.create', compact('specialites', 'subjectTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects,code',
            'specialite_id' => 'nullable|exists:specialities,id',
            'subject_type_id' => 'required|exists:subject_types,id'
        ]);

        Subject::create($request->all());

        return redirect()->route('web.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $specialites = Speciality::all();
        $subjectTypes = SubjectType::orderBy('order')->get();
        return view('admin.subjects.edit', compact('subject', 'specialites', 'subjectTypes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects,code,' . $subject->id,
            'specialite_id' => 'nullable|exists:specialities,id',
            'subject_type_id' => 'required|exists:subject_types,id'
        ]);

        $subject->update($request->all());

        return redirect()->route('web.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['success' => true]);
    }
}
