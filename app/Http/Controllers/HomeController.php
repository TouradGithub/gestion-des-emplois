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
            return redirect()->route('web.dashboard'); // توجيه للدشبورد الجديد للأدمن
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        return view('home');
    }
}
