<?php

namespace App\Http\Controllers;

use App\Models\TeacherRequest;
use App\Models\SubjectTeacher;
use App\Models\Classe;
use App\Models\Jour;
use App\Models\Horaire;
use App\Models\SalleDeClasse;
use App\Models\Anneescolaire;
use App\Models\Trimester;
use App\Models\EmploiTemps;
use App\Models\EmploiHoraire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.type:teacher');
    }

    /**
     * عرض قائمة طلبات الأستاذ
     */
    public function index()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Enseignant non trouvé');
        }

        $requests = TeacherRequest::with(['subject', 'classe', 'jour', 'horaire', 'salle', 'trimester'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('teacher.requests.index', compact('requests', 'teacher'));
    }

    /**
     * عرض نموذج إنشاء طلب جديد
     */
    public function create()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Enseignant non trouvé');
        }

        $anneeActive = Anneescolaire::where('is_active', true)->first();

        if (!$anneeActive) {
            return redirect()->route('teacher.requests.index')->with('error', 'Aucune année scolaire active');
        }

        // جلب المواد التي يدرسها الأستاذ
        $subjectTeachers = SubjectTeacher::with(['subject', 'classe'])
            ->where('teacher_id', $teacher->id)
            ->where('annee_id', $anneeActive->id)
            ->get();

        $jours = Jour::orderBy('ordre')->get();
        $trimesters = Trimester::all();

        return view('teacher.requests.create', compact('teacher', 'subjectTeachers', 'jours', 'trimesters', 'anneeActive'));
    }

    /**
     * Récupérer les matières selon la classe choisie
     */
    public function getSubjectsByClass(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $classId = $request->class_id;
        $anneeActive = Anneescolaire::where('is_active', true)->first();

        $subjects = SubjectTeacher::with('subject')
            ->where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('annee_id', $anneeActive->id)
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->values();

        return response()->json(['subjects' => $subjects]);
    }

    /**
     * Récupérer les trimestres selon la classe choisie
     */
    public function getTrimestersByClass(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $classId = $request->class_id;
        $anneeActive = Anneescolaire::where('is_active', true)->first();

        // Récupérer les trimestres où l'enseignant enseigne cette classe
        $trimesters = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('annee_id', $anneeActive->id)
            ->with('trimester')
            ->get()
            ->pluck('trimester')
            ->unique('id')
            ->filter()
            ->values();

        // Si pas de trimestres spécifiques, retourner tous les trimestres
        if ($trimesters->isEmpty()) {
            $trimesters = Trimester::all();
        }

        return response()->json(['trimesters' => $trimesters]);
    }

    /**
     * جلب الأوقات المتاحة حسب اليوم
     */
    public function getAvailableHoraires(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $jourId = $request->jour_id;
        $classId = $request->class_id;
        $anneeActive = Anneescolaire::where('is_active', true)->first();

        // جلب جميع الأوقات
        $allHoraires = Horaire::orderBy('ordre')->get();

        // جلب الأوقات المشغولة للأستاذ في هذا اليوم
        $teacherBusyHoraires = EmploiTemps::where('teacher_id', $teacher->id)
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeActive->id)
            ->with('ref_horaires')
            ->get()
            ->pluck('ref_horaires')
            ->flatten()
            ->pluck('id')
            ->toArray();

        // جلب الأوقات المشغولة للقسم في هذا اليوم
        $classBusyHoraires = EmploiTemps::where('class_id', $classId)
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeActive->id)
            ->with('ref_horaires')
            ->get()
            ->pluck('ref_horaires')
            ->flatten()
            ->pluck('id')
            ->toArray();

        // جلب الأوقات المحجوزة في طلبات معلقة
        $pendingRequestHoraires = TeacherRequest::where('status', 'pending')
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeActive->id)
            ->where(function($q) use ($teacher, $classId) {
                $q->where('teacher_id', $teacher->id)
                  ->orWhere('class_id', $classId);
            })
            ->pluck('horaire_id')
            ->toArray();

        $busyHoraires = array_unique(array_merge($teacherBusyHoraires, $classBusyHoraires, $pendingRequestHoraires));

        // تصفية الأوقات المتاحة
        $availableHoraires = $allHoraires->filter(function($horaire) use ($busyHoraires) {
            return !in_array($horaire->id, $busyHoraires);
        })->values();

        return response()->json(['horaires' => $availableHoraires]);
    }

    /**
     * جلب القاعات المتاحة
     */
    public function getAvailableSalles(Request $request)
    {
        $jourId = $request->jour_id;
        $horaireId = $request->horaire_id;
        $anneeActive = Anneescolaire::where('is_active', true)->first();

        // جلب جميع القاعات
        $allSalles = SalleDeClasse::all();

        // جلب القاعات المشغولة في هذا الوقت واليوم
        $busySalleIds = EmploiTemps::where('jour_id', $jourId)
            ->where('annee_id', $anneeActive->id)
            ->whereHas('ref_horaires', function($q) use ($horaireId) {
                $q->where('sct_ref_horaires.id', $horaireId);
            })
            ->pluck('salle_de_classe_id')
            ->toArray();

        // جلب القاعات المحجوزة في طلبات معلقة
        $pendingSalleIds = TeacherRequest::where('status', 'pending')
            ->where('jour_id', $jourId)
            ->where('horaire_id', $horaireId)
            ->where('annee_id', $anneeActive->id)
            ->pluck('salle_de_classe_id')
            ->toArray();

        $allBusySalles = array_unique(array_merge($busySalleIds, $pendingSalleIds));

        // تصفية القاعات المتاحة
        $availableSalles = $allSalles->filter(function($salle) use ($allBusySalles) {
            return !in_array($salle->id, $allBusySalles);
        })->values();

        return response()->json(['salles' => $availableSalles]);
    }

    /**
     * حفظ الطلب الجديد
     */
    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'jour_id' => 'required|exists:sct_refjours,id',
            'horaire_id' => 'required|exists:sct_ref_horaires,id',
            'salle_de_classe_id' => 'required|exists:salle_de_classes,id',
            'trimester_id' => 'required|exists:trimesters,id',
            'note' => 'nullable|string|max:500',
        ], [
            'subject_id.required' => 'La matière est obligatoire',
            'subject_id.exists' => 'La matière sélectionnée est invalide',
            'class_id.required' => 'La classe est obligatoire',
            'class_id.exists' => 'La classe sélectionnée est invalide',
            'jour_id.required' => 'Le jour est obligatoire',
            'jour_id.exists' => 'Le jour sélectionné est invalide',
            'horaire_id.required' => 'L\'horaire est obligatoire',
            'horaire_id.exists' => 'L\'horaire sélectionné est invalide',
            'salle_de_classe_id.required' => 'La salle est obligatoire',
            'salle_de_classe_id.exists' => 'La salle sélectionnée est invalide',
            'trimester_id.required' => 'Le trimestre est obligatoire',
            'trimester_id.exists' => 'Le trimestre sélectionné est invalide',
            'note.max' => 'La note ne peut pas dépasser 500 caractères',
        ]);

        $anneeActive = Anneescolaire::where('is_active', true)->first();

        // التحقق من عدم وجود تعارض
        $conflict = $this->checkConflict(
            $teacher->id,
            $request->class_id,
            $request->jour_id,
            $request->horaire_id,
            $request->salle_de_classe_id,
            $anneeActive->id
        );

        if ($conflict) {
            return back()->with('error', $conflict)->withInput();
        }

        TeacherRequest::create([
            'teacher_id' => $teacher->id,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'jour_id' => $request->jour_id,
            'horaire_id' => $request->horaire_id,
            'salle_de_classe_id' => $request->salle_de_classe_id,
            'annee_id' => $anneeActive->id,
            'trimester_id' => $request->trimester_id,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('teacher.requests.index')->with('success', 'Demande envoyée avec succès');
    }

    /**
     * التحقق من التعارضات
     */
    private function checkConflict($teacherId, $classId, $jourId, $horaireId, $salleId, $anneeId)
    {
        // Vérifier le conflit de l'enseignant
        $teacherConflict = EmploiTemps::where('teacher_id', $teacherId)
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeId)
            ->whereHas('ref_horaires', function($q) use ($horaireId) {
                $q->where('sct_ref_horaires.id', $horaireId);
            })
            ->exists();

        if ($teacherConflict) {
            return 'Vous avez une autre séance au même moment';
        }

        // Vérifier le conflit de la classe
        $classConflict = EmploiTemps::where('class_id', $classId)
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeId)
            ->whereHas('ref_horaires', function($q) use ($horaireId) {
                $q->where('sct_ref_horaires.id', $horaireId);
            })
            ->exists();

        if ($classConflict) {
            return 'La classe a une autre séance au même moment';
        }

        // Vérifier le conflit de la salle
        $salleConflict = EmploiTemps::where('salle_de_classe_id', $salleId)
            ->where('jour_id', $jourId)
            ->where('annee_id', $anneeId)
            ->whereHas('ref_horaires', function($q) use ($horaireId) {
                $q->where('sct_ref_horaires.id', $horaireId);
            })
            ->exists();

        if ($salleConflict) {
            return 'La salle est occupée au même moment';
        }

        // التحقق من طلب معلق مشابه
        $pendingConflict = TeacherRequest::where('status', 'pending')
            ->where('jour_id', $jourId)
            ->where('horaire_id', $horaireId)
            ->where('annee_id', $anneeId)
            ->where(function($q) use ($teacherId, $classId, $salleId) {
                $q->where('teacher_id', $teacherId)
                  ->orWhere('class_id', $classId)
                  ->orWhere('salle_de_classe_id', $salleId);
            })
            ->exists();

        if ($pendingConflict) {
            return 'Une autre demande en attente existe au même moment';
        }

        return null;
    }

    /**
     * حذف الطلب (فقط إذا كان معلقاً)
     */
    public function destroy(TeacherRequest $teacherRequest)
    {
        $teacher = auth()->user()->teacher;

        if ($teacherRequest->teacher_id !== $teacher->id) {
            return response()->json(['success' => false, 'message' => 'Non autorisé à supprimer cette demande'], 403);
        }

        if (!$teacherRequest->isPending()) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer une demande déjà traitée'], 400);
        }

        $teacherRequest->delete();

        return response()->json(['success' => true, 'message' => 'Demande supprimée avec succès']);
    }
}
