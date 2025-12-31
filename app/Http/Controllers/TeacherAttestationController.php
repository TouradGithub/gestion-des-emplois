<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttestation;
use Illuminate\Http\Request;

class TeacherAttestationController extends Controller
{
    /**
     * Display a listing of the teacher's attestations.
     */
    public function index()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Profil enseignant non trouvé.');
        }

        $attestations = TeacherAttestation::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.attestations.index', compact('attestations'));
    }

    /**
     * Show the form for creating a new attestation request.
     */
    public function create()
    {
        return view('teacher.attestations.create');
    }

    /**
     * Store a newly created attestation request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:travail,salaire,experience',
            'motif' => 'nullable|string|max:500',
        ], [
            'type.required' => 'Veuillez sélectionner le type d\'attestation.',
            'type.in' => 'Type d\'attestation invalide.',
        ]);

        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard')->with('error', 'Profil enseignant non trouvé.');
        }

        // Check for pending requests of same type
        $existingPending = TeacherAttestation::where('teacher_id', $teacher->id)
            ->where('type', $request->type)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande en attente pour ce type d\'attestation.');
        }

        TeacherAttestation::create([
            'teacher_id' => $teacher->id,
            'type' => $request->type,
            'motif' => $request->motif,
            'status' => 'pending',
        ]);

        return redirect()->route('teacher.attestations.index')
            ->with('success', 'Votre demande d\'attestation a été soumise avec succès.');
    }

    /**
     * Download approved attestation as PDF.
     */
    public function download(TeacherAttestation $attestation)
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher || $attestation->teacher_id !== $teacher->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($attestation->status !== 'approved') {
            return redirect()->back()->with('error', 'Cette attestation n\'est pas encore approuvée.');
        }

        // Generate PDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Attestation - ' . $attestation->attestation_number);
        $mpdf->SetAuthor(config('app.name'));

        $html = view('teacher.attestations.pdf', [
            'attestation' => $attestation,
            'teacher' => $teacher,
        ])->render();

        $mpdf->WriteHTML($html);

        return $mpdf->Output('attestation_' . $attestation->attestation_number . '.pdf', 'I');
    }

    /**
     * Cancel a pending attestation request.
     */
    public function destroy(TeacherAttestation $attestation)
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher || $attestation->teacher_id !== $teacher->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($attestation->status !== 'pending') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        $attestation->delete();

        return redirect()->route('teacher.attestations.index')
            ->with('success', 'La demande a été annulée avec succès.');
    }
}
