<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauPedagogique extends Model
{
    protected $fillable = [
        'nom', 'ordre', 'formation_id'
    ];

    public function formation()
    {
        return $this->belongsTo(Niveauformation::class);
    }
    public function trimesters()
    {
        return $this->hasMany(Trimester::class, 'niveau_pedagogique_id');
    }
}
