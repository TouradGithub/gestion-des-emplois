<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speciality extends Model
{
    protected $table = 'specialities';
    protected $fillable = ['name','code', 'departement_id','niveau_formation_id'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveauformation::class , 'niveau_formation_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'specialite_id');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'specialite_id');
    }
}
