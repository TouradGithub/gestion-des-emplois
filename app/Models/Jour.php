<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jour extends Model
{
    protected $table = 'sct_refjours';
    protected $fillable = ['libelle_fr','libelle_ar','ordre'];

    public function emplois()
    {
        return $this->hasMany(EmploiTemps::class);
    }
}
