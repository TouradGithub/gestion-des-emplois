<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Departement;
use App\Models\EmploiTemps;
use App\Models\EmploiHoraire;
use App\Models\SubjectTeacher;
use App\Models\Pointage;
use App\Models\Anneescolaire;
use App\Models\Trimester;
use App\Models\Jour;
use App\Models\Horaire;
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
        $query = $teacher->pointages()
                        ->with(['emploiTemps.classe', 'emploiTemps.subject', 'emploiTemps.horairess', 'emploiTemps.jour'])
                        ->orderBy('date_pointage', 'desc');
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

    /**
     * عرض الجدول الزمني للأستاذ
     */
    public function emploiTemps()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', __('teacher.teacher_not_found'));
        }

        $anneeId = Anneescolaire::where('is_active', 1)->value('id');

        // جلب جميع حصص الأستاذ للسنة النشطة
        $emplois = EmploiTemps::with(['classe', 'subject', 'jour', 'salle', 'ref_horaires'])
            ->where('teacher_id', $teacher->id)
            ->where('annee_id', $anneeId)
            ->get();

        // جلب أيام الأسبوع
        $weekDays = Jour::orderBy('ordre')->get();

        // بناء بيانات التقويم
        $calendarData = $this->generateTeacherCalendar($weekDays, $emplois);

        return view('teacher.emploi-temps', compact('teacher', 'calendarData', 'weekDays'));
    }

    /**
     * بناء بيانات التقويم للأستاذ
     */
    private function generateTeacherCalendar($weekDays, $emplois)
    {
        $calendarData = [];
        $timeRange = Horaire::orderBy('ordre')->get();
        $printed_details = [];

        foreach ($timeRange as $time) {
            $timeText = $time->libelle_fr;
            $calendarData[$timeText] = [];

            foreach ($weekDays as $day) {
                $detail = $emplois->where('jour_id', $day->id)
                    ->whereIn('id',
                        EmploiHoraire::where('horaire_id', $time->id)
                            ->pluck('emploi_temps_id')
                            ->toArray()
                    )->first();

                if ($detail && !in_array($detail->id . '_' . $day->id, $printed_details)) {
                    $horaire = $detail->getHoraires();
                    $rowspan = abs(Carbon::parse($horaire[1])->diffInMinutes($horaire[0]) / 60);

                    $calendarData[$timeText][] = [
                        'matiere' => $detail->subject->name ?? '-',
                        'classe' => $detail->classe->nom ?? '-',
                        'salle' => $detail->salle->name ?? null,
                        'rowspan' => $rowspan ?: 1,
                        'date' => $day->libelle_fr,
                        'id' => $detail->id,
                        'emploi' => $detail,
                    ];
                    $printed_details[] = $detail->id . '_' . $day->id;
                } else if (!$emplois->where('jour_id', $day->id)
                    ->whereIn('id',
                        EmploiHoraire::query()
                            ->whereIn('horaire_id',
                                Horaire::query()
                                    ->where('start_time', '<', $time->start_time)
                                    ->where('end_time', '>=', $time->end_time)
                                    ->pluck('id'))
                            ->pluck('emploi_temps_id'))->count()
                ) {
                    $calendarData[$timeText][] = 1; // خلية فارغة
                } else {
                    $calendarData[$timeText][] = 0; // خلية مدمجة (rowspan)
                }
            }
        }

        return $calendarData;
    }
}
