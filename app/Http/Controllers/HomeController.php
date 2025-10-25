<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // توجيه المستخدم حسب نوعه
        if ($user->isAdmin()) {
            return view('home'); // الصفحة الرئيسية للمدير
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('home');
    }
}
