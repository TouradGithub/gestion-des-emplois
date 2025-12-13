<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasseStudent extends Model
{
    protected $table = 'classe_student';

    protected $fillable = [
        'student_id',
        'classe_id',
        'annee_id',
    ];

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the classe
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Get the school year
     */
    public function annee()
    {
        return $this->belongsTo(Anneescolaire::class, 'annee_id');
    }
}
