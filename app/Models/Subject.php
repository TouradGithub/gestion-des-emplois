<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    protected $fillable = ['name', 'code', 'specialite_id', 'subject_type_id'];

    public function specialite()
    {
        return $this->belongsTo(Speciality::class, 'specialite_id');
    }

    public function subjectTeachers()
    {
        return $this->hasMany(SubjectTeacher::class, 'subject_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher', 'subject_id', 'teacher_id')
                    ->withPivot(['trimester_id', 'class_id', 'annee_id'])
                    ->withTimestamps();
    }

    /**
     * Get the type of this subject (TD, TP, Project)
     */
    public function subjectType()
    {
        return $this->belongsTo(SubjectType::class, 'subject_type_id');
    }

    /**
     * Alias for subjectType
     */
    public function type()
    {
        return $this->subjectType();
    }
}
