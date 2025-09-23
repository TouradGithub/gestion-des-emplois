<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trimester extends Model
{
    protected $fillable = ['name', 'niveau_pedagogique_id'];

    public function niveau()
    {
        return $this->belongsTo(NiveauPedagogique::class, 'niveau_pedagogique_id');
    }
}
