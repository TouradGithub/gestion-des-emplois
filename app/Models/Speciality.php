<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Speciality extends Model
{
    protected $fillable = ['name','code', 'departement_id','niveau_formation_id'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveauformation::class , 'niveau_formation_id');
    }
}
