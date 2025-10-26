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
        'sys_user_id',
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

    public function subjectTeachers()
    {
        return $this->hasMany(SubjectTeacher::class, 'teacher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sys_user_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Departement::class, 'subject_teacher', 'teacher_id', 'subject_id')
                    ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
                    ->join('specialities', 'subjects.specialite_id', '=', 'specialities.id')
                    ->join('departements', 'specialities.departement_id', '=', 'departements.id')
                    ->distinct();
    }

    /**
     * Relation avec les pointages
     */
    public function pointages()
    {
        return $this->hasMany(Pointage::class, 'teacher_id');
    }

    /**
     * Relation avec les emplois du temps
     */
    public function emploisTemps()
    {
        return $this->hasMany(EmploiTemps::class, 'teacher_id');
    }
}
