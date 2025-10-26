<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{

    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nni' => 'required|string|unique:teachers,nni',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'gender' => 'required|in:0,1',
            'image' => 'nullable|image|max:2048', // حد أقصى 2 ميغا
        ]);
        try {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->nni),
                'etat' => 1,
                 'sys_types_user_id' => 2
            ]);

            $teacherData = $request->only(['name', 'nni', 'phone', 'email', 'dob', 'gender']);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('teachers_images', 'public');
                $teacherData['image'] = $path;
            }
            $teacherData['sys_user_id'] = $user->id;

            Teacher::create($teacherData);

            DB::commit();

            return redirect()->route('web.teachers.index')->with('success', 'Enseignant et utilisateur créés avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Une erreur est survenue lors de la création. Veuillez réessayer.'.$e->getMessage())->withInput();
        }

    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nni' => 'required|string|unique:teachers,nni,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:teachers,email,' . $teacher->id,
            'dob' => 'nullable|date',
            'gender' => 'required|in:0,1',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $teacherData = $request->only(['name', 'nni', 'phone', 'email', 'dob', 'gender']);

            // التعامل مع الصورة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($teacher->image && file_exists(storage_path('app/public/' . $teacher->image))) {
                    unlink(storage_path('app/public/' . $teacher->image));
                }

                $path = $request->file('image')->store('teachers_images', 'public');
                $teacherData['image'] = $path;
            }

            $teacher->update($teacherData);

            // تحديث بيانات المستخدم المرتبط
            if ($teacher->sys_user_id) {
                $user = \App\Models\User::find($teacher->sys_user_id);
                if ($user) {
                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('web.teachers.index')->with('success', 'تم تحديث معلومات الأستاذ بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('حدث خطأ أثناء التحديث: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            // حذف المستخدم المرتبط بالأستاذ
            if ($teacher->sys_user_id) {
                $user = \App\Models\User::find($teacher->sys_user_id);
                if ($user) {
                    $user->delete();
                }
            }

            $teacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الأستاذ بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Teacher::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

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
                $operate .= '<a class="btn btn-xs btn-gradient-danger delete-teacher" data-id="' . $teacher->id . '" data-url="' . route('web.teachers.destroy', $teacher->id) . '"><i class="fa fa-trash"></i></a>';
//            }

            $rows[] = [
                'id' => $teacher->id,
                'no' => $no++,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'nni' => $teacher->nni,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

}
