<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRequest;
use App\Models\EmploiTemps;
use App\Models\EmploiHoraire;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.type:admin');
    }

    /**
     * Afficher la liste de toutes les demandes
     */
    public function index(Request $request)
    {
        $query = TeacherRequest::with(['teacher.user', 'subject', 'classe', 'jour', 'horaire', 'salle', 'trimester', 'approvedBy']);

        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrer par enseignant
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Récupérer les enseignants pour le filtre
        $teachers = \App\Models\Teacher::with('user')->get();

        return view('admin.teacher-requests.index', compact('requests', 'teachers'));
    }

    /**
     * Afficher les détails de la demande
     */
    public function show(TeacherRequest $teacherRequest)
    {
        $teacherRequest->load(['teacher.user', 'subject', 'classe', 'jour', 'horaire', 'salle', 'trimester', 'approvedBy']);

        return view('admin.teacher-requests.show', compact('teacherRequest'));
    }

    /**
     * Approuver la demande et créer la séance
     */
    public function approve(Request $request, TeacherRequest $teacherRequest)
    {
        if (!$teacherRequest->isPending()) {
            return response()->json(['success' => false, 'message' => 'Cette demande a déjà été traitée'], 400);
        }

        // Vérifier les conflits à nouveau
        $conflict = $this->checkConflict($teacherRequest);
        if ($conflict) {
            return response()->json(['success' => false, 'message' => $conflict], 400);
        }

        DB::beginTransaction();

        try {
            // Créer la séance
            $emploi = EmploiTemps::create([
                'class_id' => $teacherRequest->class_id,
                'subject_id' => $teacherRequest->subject_id,
                'teacher_id' => $teacherRequest->teacher_id,
                'trimester_id' => $teacherRequest->trimester_id,
                'annee_id' => $teacherRequest->annee_id,
                'jour_id' => $teacherRequest->jour_id,
                'salle_de_classe_id' => $teacherRequest->salle_de_classe_id,
            ]);

            // Associer la séance à l'horaire
            $emploi->ref_horaires()->attach($teacherRequest->horaire_id);

            // Vérifier si subject_teacher existe et le créer si nécessaire
            $subjectTeacher = SubjectTeacher::where('teacher_id', $teacherRequest->teacher_id)
                ->where('subject_id', $teacherRequest->subject_id)
                ->where('class_id', $teacherRequest->class_id)
                ->where('trimester_id', $teacherRequest->trimester_id)
                ->where('annee_id', $teacherRequest->annee_id)
                ->first();

            if (!$subjectTeacher) {
                SubjectTeacher::create([
                    'teacher_id' => $teacherRequest->teacher_id,
                    'subject_id' => $teacherRequest->subject_id,
                    'class_id' => $teacherRequest->class_id,
                    'trimester_id' => $teacherRequest->trimester_id,
                    'annee_id' => $teacherRequest->annee_id,
                    'heures_trimestre' => 24,
                ]);
            }

            // Mettre à jour le statut de la demande
            $teacherRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_note' => $request->admin_note,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Demande approuvée et séance créée avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement de la demande: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refuser la demande
     */
    public function reject(Request $request, TeacherRequest $teacherRequest)
    {
        if (!$teacherRequest->isPending()) {
            return response()->json(['success' => false, 'message' => 'Cette demande a déjà été traitée'], 400);
        }

        $teacherRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demande refusée'
        ]);
    }

    /**
     * Vérifier les conflits
     */
    private function checkConflict(TeacherRequest $request)
    {
        // Vérifier le conflit de l'enseignant
        $teacherConflict = EmploiTemps::where('teacher_id', $request->teacher_id)
            ->where('jour_id', $request->jour_id)
            ->where('annee_id', $request->annee_id)
            ->whereHas('ref_horaires', function($q) use ($request) {
                $q->where('sct_ref_horaires.id', $request->horaire_id);
            })
            ->exists();

        if ($teacherConflict) {
            return 'L\'enseignant a une autre séance au même moment';
        }

        // Vérifier le conflit de la classe
        $classConflict = EmploiTemps::where('class_id', $request->class_id)
            ->where('jour_id', $request->jour_id)
            ->where('annee_id', $request->annee_id)
            ->whereHas('ref_horaires', function($q) use ($request) {
                $q->where('sct_ref_horaires.id', $request->horaire_id);
            })
            ->exists();

        if ($classConflict) {
            return 'La classe a une autre séance au même moment';
        }

        // Vérifier le conflit de la salle
        $salleConflict = EmploiTemps::where('salle_de_classe_id', $request->salle_de_classe_id)
            ->where('jour_id', $request->jour_id)
            ->where('annee_id', $request->annee_id)
            ->whereHas('ref_horaires', function($q) use ($request) {
                $q->where('sct_ref_horaires.id', $request->horaire_id);
            })
            ->exists();

        if ($salleConflict) {
            return 'La salle est occupée au même moment';
        }

        return null;
    }
}
