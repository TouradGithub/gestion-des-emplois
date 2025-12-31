<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherAttestation;
use Illuminate\Http\Request;

class AttestationController extends Controller
{
    /**
     * Display a listing of all attestation requests.
     */
    public function index(Request $request)
    {
        $query = TeacherAttestation::with(['teacher', 'approver']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by teacher
        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $attestations = $query->orderBy('created_at', 'desc')->paginate(15);
        $teachers = \App\Models\Teacher::orderBy('name')->get();

        // Statistics
        $stats = [
            'pending' => TeacherAttestation::where('status', 'pending')->count(),
            'approved' => TeacherAttestation::where('status', 'approved')->count(),
            'rejected' => TeacherAttestation::where('status', 'rejected')->count(),
            'total' => TeacherAttestation::count(),
        ];

        return view('admin.attestations.index', compact('attestations', 'teachers', 'stats'));
    }

    /**
     * Show a specific attestation request.
     */
    public function show(TeacherAttestation $attestation)
    {
        $attestation->load(['teacher', 'approver']);
        return view('admin.attestations.show', compact('attestation'));
    }

    /**
     * Approve an attestation request.
     */
    public function approve(TeacherAttestation $attestation)
    {
        if ($attestation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée.'
            ], 400);
        }

        $attestation->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'attestation_number' => TeacherAttestation::generateAttestationNumber(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'L\'attestation a été approuvée avec succès.',
            'attestation_number' => $attestation->attestation_number,
        ]);
    }

    /**
     * Reject an attestation request.
     */
    public function reject(Request $request, TeacherAttestation $attestation)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Veuillez indiquer le motif du rejet.',
        ]);

        if ($attestation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande a déjà été traitée.'
            ], 400);
        }

        $attestation->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La demande a été rejetée.',
        ]);
    }

    /**
     * Preview/Download an attestation PDF.
     */
    public function downloadPdf(TeacherAttestation $attestation)
    {
        if ($attestation->status !== 'approved') {
            return redirect()->back()->with('error', 'Seules les attestations approuvées peuvent être téléchargées.');
        }

        $attestation->load('teacher');

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
            'teacher' => $attestation->teacher,
        ])->render();

        $mpdf->WriteHTML($html);

        return $mpdf->Output('attestation_' . $attestation->attestation_number . '.pdf', 'I');
    }
}
