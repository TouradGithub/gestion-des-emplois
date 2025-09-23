<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    protected $table = 'sct_ref_horaires';
    protected $fillable = ['libelle_fr','libelle_ar','ordre','start_time', 'end_time'];

//    public function emplois()
//    {
//        return $this->hasMany(EmploiTemps::class);
//    }
}
