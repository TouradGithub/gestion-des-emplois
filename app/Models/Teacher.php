<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['name',
        'nni',
        'phone',
        'email',
        'gender',
        'image',
        'dob',
    ];
    public function subjectTeacher()
    {
        return $this->hasMany(SubjectTeacher::class, 'teacher_id')->where('annee_id', Anneescolaire::where('is_active', 1)->first()->id);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id')
                    ->withPivot(['trimester_id', 'class_id', 'annee_id'])
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->hasMany(SubjectTeacher::class, 'teacher_id');
    }
}
