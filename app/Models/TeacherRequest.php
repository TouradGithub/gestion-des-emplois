<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherRequest extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id',
        'jour_id',
        'horaire_id',
        'salle_de_classe_id',
        'annee_id',
        'trimester_id',
        'status',
        'note',
        'admin_note',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class);
    }

    public function salle()
    {
        return $this->belongsTo(SalleDeClasse::class, 'salle_de_classe_id');
    }

    public function annee()
    {
        return $this->belongsTo(Anneescolaire::class, 'annee_id');
    }

    public function trimester()
    {
        return $this->belongsTo(Trimester::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">En attente</span>',
            'approved' => '<span class="badge bg-success">Approuvé</span>',
            'rejected' => '<span class="badge bg-danger">Refusé</span>',
            default => '<span class="badge bg-secondary">Inconnu</span>',
        };
    }
}
