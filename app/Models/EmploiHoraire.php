<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiHoraire extends Model
{

    protected $table = 'emploi_horaire';

    protected $fillable = [
        'emploi_temps_id',
        'horaire_id',
    ];

    public function emploi()
    {
        return $this->belongsTo(EmploiTemps::class, 'emploi_temps_id');
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class, 'horaire_id');
    }
}
