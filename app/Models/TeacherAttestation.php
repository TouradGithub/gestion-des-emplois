<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAttestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'type',
        'motif',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'attestation_number',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the teacher that owns the attestation.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the user who approved the attestation.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get type label in French
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'travail' => 'Attestation de travail',
            'salaire' => 'Attestation de salaire',
            'experience' => 'Attestation d\'expérience',
            default => $this->type,
        };
    }

    /**
     * Get status label in French
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée',
            default => $this->status,
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Generate unique attestation number
     */
    public static function generateAttestationNumber()
    {
        $year = date('Y');
        $lastAttestation = self::whereYear('created_at', $year)
            ->whereNotNull('attestation_number')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAttestation && preg_match('/ATT-' . $year . '-(\d+)/', $lastAttestation->attestation_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'ATT-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
