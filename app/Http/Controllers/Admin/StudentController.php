<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['classe', 'user'])->latest()->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Classe::with(['niveau', 'specialite', 'annee'])->get();
        return view('admin.students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nni' => 'required|string|unique:students,nni|max:20',
            'fullname' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Create user account for student with role_id = 3
            $user = User::create([
                'name' => $request->fullname,
                'email' => $request->nni, // Use NNI as email
                'password' => Hash::make($request->nni), // Use NNI as default password
                'role_id' => 3 // Student role
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('students', 'public');
            }

            // Create student record
            Student::create([
                'nni' => $request->nni,
                'fullname' => $request->fullname,
                'parent_name' => $request->parent_name,
                'phone' => $request->phone,
                'class_id' => $request->class_id,
                'user_id' => $user->id,
                'image' => $imagePath
            ]);

            return redirect()->route('web.students.index')
                           ->with('success', 'تم إضافة الطالب بنجاح وإنشاء حسابه');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'حدث خطأ أثناء إضافة الطالب: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['classe.niveau', 'classe.specialite', 'classe.annee', 'user', 'schedule']);
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = Classe::with(['niveau', 'specialite', 'annee'])->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nni' => 'required|string|max:20|unique:students,nni,' . $student->id,
            'fullname' => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Update user account
            $student->user->update([
                'name' => $request->fullname,
                'email' => $request->nni . '@student.local'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($student->image) {
                    Storage::disk('public')->delete($student->image);
                }
                $imagePath = $request->file('image')->store('students', 'public');
            } else {
                $imagePath = $student->image;
            }

            // Update student record
            $student->update([
                'nni' => $request->nni,
                'fullname' => $request->fullname,
                'parent_name' => $request->parent_name,
                'phone' => $request->phone,
                'class_id' => $request->class_id,
                'image' => $imagePath
            ]);

            return redirect()->route('web.students.index')
                           ->with('success', 'تم تحديث بيانات الطالب بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'حدث خطأ أثناء تحديث بيانات الطالب: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            // Delete image if exists
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            // Delete user account
            $student->user->delete();

            // Delete student record
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطالب بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الطالب: ' . $e->getMessage()
            ], 500);
        }
    }
}
