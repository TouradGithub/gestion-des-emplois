<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anneescolaire extends Model
{
    protected $fillable = ['annee','date_debut','date_fin', 'is_active'];

    /**
     * Get the classes that belong to this school year.
     */
    public function classes()
    {
        return $this->hasMany(Classe::class, 'annee_id');
    }
}
