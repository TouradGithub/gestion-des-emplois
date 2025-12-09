<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pointage extends Model
{
    protected $table = 'pointages';

    protected $fillable = [
        'emploi_temps_id',
        'teacher_id',
        'date_pointage',
        'statut',
        'remarques',
        'created_by'
    ];

    protected $casts = [
        'date_pointage' => 'date',
    ];

    /**
     * Relation avec EmploiTemps
     */
    public function emploiTemps(): BelongsTo
    {
        return $this->belongsTo(EmploiTemps::class, 'emploi_temps_id');
    }

    /**
     * Relation avec Teacher
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Relation avec User (créateur du pointage)
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope pour filtrer par professeur
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('date_pointage', $date);
    }

    /**
     * Scope pour les présents
     */
    public function scopePresent($query)
    {
        return $query->where('statut', 'present');
    }

    /**
     * Scope pour les absents
     */
    public function scopeAbsent($query)
    {
        return $query->where('statut', 'absent');
    }

    /**
     * Vérifier si le professeur est présent
     */
    public function isPresent(): bool
    {
        return $this->statut === 'present';
    }

    /**
     * Obtenir le statut en français
     */
    public function getStatutFrAttribute(): string
    {
        return $this->statut === 'present' ? 'Présent' : 'Absent';
    }

    /**
     * Obtenir le statut en arabe
     */
    public function getStatutArAttribute(): string
    {
        return $this->statut === 'present' ? 'حاضر' : 'غائب';
    }
}
