<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classe;
use App\Models\Subject;
use App\Models\EmploiTemps;
use App\Models\Pointage;
use App\Models\Departement;
use App\Models\Speciality;
use App\Models\SubjectTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.type:admin');
    }

    public function index()
    {
        // الإحصائيات العامة
        $stats = [
            'total_teachers' => Teacher::count(),
            'total_classes' => Classe::count(),
            'total_subjects' => Subject::count(),
            'total_departments' => Departement::count(),
            'total_specialities' => Speciality::count(),
            'total_emplois' => EmploiTemps::count(),
            'total_pointages' => Pointage::count(),
            'total_users' => User::count(),
        ];

        // إحصائيات هذا الشهر
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyStats = [
            'new_teachers_this_month' => Teacher::whereMonth('created_at', $currentMonth)
                                                ->whereYear('created_at', $currentYear)
                                                ->count(),
            'pointages_this_month' => Pointage::whereMonth('created_at', $currentMonth)
                                              ->whereYear('created_at', $currentYear)
                                              ->count(),
        ];

        // الأساتذة النشطين (لديهم pointages في آخر 30 يوم)
        $activeTeachers = Teacher::whereHas('pointages', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        })->count();

        // احدث الأساتذة المضافين
        $recentTeachers = Teacher::with('user')
                                ->whereNotNull('created_at')
                                ->latest()
                                ->take(5)
                                ->get();

        // الأقسام مع عدد الاختصاصات في كل قسم
        $departmentStats = Departement::withCount('specialities')->take(10)->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyStats',
            'activeTeachers',
            'recentTeachers',
            'departmentStats'
        ));
    }

    // دالة لجلب الإحصائيات عبر AJAX
    public function getStats(Request $request)
    {
        $type = $request->get('type', 'weekly');

        switch ($type) {
            case 'weekly':
                return $this->getWeeklyStats();
            case 'monthly':
                return $this->getMonthlyStats();
            case 'yearly':
                return $this->getYearlyStats();
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }

    private function getWeeklyStats()
    {
        $stats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $stats[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'teachers' => Teacher::whereDate('created_at', $date)->count(),
                'pointages' => Pointage::whereDate('created_at', $date)->count(),
                'emplois' => EmploiTemps::whereDate('created_at', $date)->count(),
            ];
        }

        return response()->json($stats);
    }

    private function getMonthlyStats()
    {
        $stats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $stats[] = [
                'date' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'teachers' => Teacher::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count(),
                'pointages' => Pointage::whereMonth('created_at', $date->month)
                                      ->whereYear('created_at', $date->year)
                                      ->count(),
            ];
        }

        return response()->json($stats);
    }

    private function getYearlyStats()
    {
        $stats = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $stats[] = [
                'date' => $year,
                'label' => $year,
                'teachers' => Teacher::whereYear('created_at', $year)->count(),
                'pointages' => Pointage::whereYear('created_at', $year)->count(),
                'emplois' => EmploiTemps::whereYear('created_at', $year)->count(),
            ];
        }

        return response()->json($stats);
    }
}
