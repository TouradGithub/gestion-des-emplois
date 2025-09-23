<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    protected  $table = 'subject_teacher';
    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo(Subject::class , 'subject_id' );
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class , 'teacher_id' );
    }
    public function annee()
    {
        return $this->belongsTo(Anneescolaire::class , 'annee_id' );
    }
    public function trimester()
    {
        return $this->belongsTo(Trimester::class , 'trimester_id' );
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }
}
