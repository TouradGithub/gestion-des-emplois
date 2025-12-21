<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = [
        'nom',
        'niveau_pedagogique_id',
        'specialite_id',
        'annee_id',
        'is_active',
        'capacity'
    ];

    public function niveau()
    {
        return $this->belongsTo(NiveauPedagogique::class, 'niveau_pedagogique_id');
    }

    public function specialite()
    {
        return $this->belongsTo(Speciality::class , 'specialite_id');
    }

    public function annee()
    {
        return $this->belongsTo(Anneescolaire::class , 'annee_id');
    }
    public function emplois()
    {
        return $this->hasMany(EmploiTemps::class, 'class_id');
    }

    /**
     * Get the students that belong to this class (current).
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get all students through pivot table (history)
     */
    public function allStudents()
    {
        return $this->belongsToMany(Student::class, 'classe_student', 'classe_id', 'student_id')
            ->withPivot('annee_id')
            ->withTimestamps();
    }

    /**
     * Get classe student history records
     */
    public function studentHistory()
    {
        return $this->hasMany(ClasseStudent::class, 'classe_id');
    }
}
