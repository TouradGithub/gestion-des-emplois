<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nni',
        'fullname',
        'parent_name',
        'phone',
        'image',
        'class_id',
        'user_id'
    ];

    /**
     * Get the class that the student belongs to.
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    /**
     * Get the user account associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student's schedule through their class.
     */
    public function schedule()
    {
        return $this->hasMany(EmploiTemps::class, 'class_id', 'class_id');
    }
}
