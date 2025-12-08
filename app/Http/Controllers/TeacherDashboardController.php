<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Departement;
use App\Models\EmploiTemps;
use App\Models\SubjectTeacher;
use App\Models\Pointage;
use App\Models\Anneescolaire;
use App\Models\Trimester;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.type:teacher');
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('home')->with('error', __('teacher.teacher_not_found'));
        }

        // الحصول على المواد التي يدرسها المعلم
        $subjectTeachers = $this->getTeacherSubjects($teacher->id);
        $departments = $this->getTeacherDepartments($teacher->id);

        // إحصائيات الأستاذ
        $stats = $this->getTeacherStats($teacher);
        $stats['total_subjects'] = count($subjectTeachers);

        // الحصول على ملخص الساعات لكل فصل
        $hoursSummary = $this->getTeacherHoursSummary($teacher->id);

        return view('teacher.dashboard', compact('teacher', 'subjectTeachers', 'departments', 'stats', 'hoursSummary'));
    }

    public function departments()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        $subjectTeachers = $this->getTeacherSubjects($teacher->id);

        return view('teacher.departments', compact('teacher', 'subjectTeachers'));
    }

    public function getTeacherSubjects($teacherId)
    {
        // الحصول على SubjectTeacher مع جميع العلاقات المطلوبة
        $subjectTeachers = SubjectTeacher::with([
                'subject.specialite.departement',
                'classe',
                'trimester',
                'annee'
            ])
            ->where('teacher_id', $teacherId)
            ->get();

        return $subjectTeachers;
    }

    public function getTeacherDepartments($teacherId)
    {
        // الحصول على SubjectTeacher للمعلم
        $subjectTeachers = SubjectTeacher::with(['subject.specialite.departement'])
                            ->where('teacher_id', $teacherId)
                            ->get();

        $departments = [];

        foreach ($subjectTeachers as $st) {
            if ($st->subject && $st->subject->specialite && $st->subject->specialite->departement) {
                $dept = $st->subject->specialite->departement;
                $departments[$dept->id] = [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'code' => $dept->code
                ];
            }
        }

        return array_values($departments);
    }

    public function showSchedule($subjectTeacherId )
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        // التحقق من أن المعلم لديه هذه المادة
        // $subjectTeacher = SubjectTeacher::with([
        //         'subject.specialite.departement',
        //         'classe',
        //         'trimester',
        //         'annee'
        //     ])
        //     ->where('id', $subjectTeacherId)
        //     ->where('teacher_id', $teacher->id)
        //     ->first();

        // if (!$subjectTeacher) {
        //     return redirect()->route('teacher.departments')->with('error', __('teacher.access_denied'));
        // }

        // جلب الجدول الزمني لهذه المادة والفصل
        $schedules = EmploiTemps::with(['classe', 'subject', 'teacher', 'horaire', 'jour', 'salle'])
                    ->where('teacher_id', $teacher->id)
                    // ->where('subject_id', $subjectTeacher->subject_id)
                    ->where('class_id', $subjectTeacherId)
                    ->orderBy('jour_id')
                    ->get();

                       $subjectTeacher = SubjectTeacher::with([
                'subject.specialite.departement',
                'classe',
                'trimester',
                'annee'
            ])
            ->where('class_id', $subjectTeacherId)
            ->where('teacher_id', $teacher->id)
            ->first();



        return view('teacher.schedule', compact('schedules', 'subjectTeacher', 'teacher'));
    }







    public function allSchedules()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        $departments = $this->getTeacherDepartments($teacher->id);
        $allSchedules = [];

        foreach ($departments as $dept) {
            $schedules = EmploiTemps::with(['classe', 'subject', 'teacher', 'horaire', 'jour'])
                        ->where('teacher_id', $teacher->id)
                        ->whereHas('classe.specialite.departement', function($q) use ($dept) {
                            $q->where('id', $dept['id']);
                        })
                        ->get();

            $allSchedules[] = [
                'department' => $dept,
                'schedules' => $schedules
            ];
        }

        return view('teacher.all-schedules', compact('allSchedules', 'teacher'));
    }

    public function showPointages(Request $request)
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        // بناء الاستعلام مع الفلاتر
        $query = $teacher->pointages()
                        ->with(['emploiTemps.classe', 'emploiTemps.subject', 'emploiTemps.horairess', 'emploiTemps.jour'])
                        ->orderBy('date_pointage', 'desc');

        // فلتر التاريخ
        if ($request->filled('from_date')) {
            $query->where('date_pointage', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('date_pointage', '<=', $request->to_date);
        }

        $pointages = $query->paginate(15)->appends($request->query());

        return view('teacher.pointages', compact('pointages', 'teacher'));
    }

    public function profile()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        return view('teacher.profile', compact('teacher'));
    }

    private function getTeacherStats($teacher)
    {
        $thisMonthPointages = $teacher->pointages()
            ->whereMonth('date_pointage', Carbon::now()->month)
            ->whereYear('date_pointage', Carbon::now()->year)
            ->count();

        // حساب إجمالي الساعات من emploi_temps
        $anneeId = Anneescolaire::where('is_active', 1)->value('id');
        $totalHoursAssigned = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('annee_id', $anneeId)
            ->sum('heures_semaine') ?? 0;

        // حساب الساعات الفعلية من emploi_temps
        $totalHoursActual = 0;
        $assignments = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('annee_id', $anneeId)
            ->get();

        foreach ($assignments as $assignment) {
            $totalHoursActual += $assignment->heures_reelles;
        }

        // حساب معدل الحضور
        $totalWorkDays = Carbon::now()->format('j');
        $attendanceRate = $totalWorkDays > 0 ? round(($thisMonthPointages / $totalWorkDays) * 100, 1) : 0;

        // حساب taux الإجمالي
        $tauxGlobal = $totalHoursAssigned > 0 ? round(($totalHoursActual / $totalHoursAssigned) * 100, 1) : 0;

        return [
            'total_hours_assigned' => $totalHoursAssigned,
            'total_hours_actual' => $totalHoursActual,
            'total_hours' => $totalHoursActual, // للتوافق مع الإصدار القديم
            'this_month_pointages' => $thisMonthPointages,
            'attendance_rate' => $attendanceRate,
            'taux_global' => $tauxGlobal,
        ];
    }

    /**
     * Get hours summary per class for teacher
     */
    private function getTeacherHoursSummary($teacherId)
    {
        $anneeId = Anneescolaire::where('is_active', 1)->value('id');

        $assignments = SubjectTeacher::where('teacher_id', $teacherId)
            ->where('annee_id', $anneeId)
            ->with(['classe', 'subject', 'trimester'])
            ->get();

        $summary = [];

        foreach ($assignments as $assignment) {
            $summary[] = [
                'id' => $assignment->id,
                'classe' => $assignment->classe->nom ?? '-',
                'subject' => $assignment->subject->name ?? '-',
                'trimester' => $assignment->trimester->name ?? '-',
                'heures_semaine' => $assignment->heures_semaine ?? 0,
                'heures_reelles' => $assignment->heures_reelles,
                'heures_restantes' => $assignment->heures_restantes,
                'taux' => $assignment->taux,
                'statut' => $assignment->statut_heures,
                'is_depasse' => $assignment->is_depasse,
            ];
        }

        return $summary;
    }
}
