<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalleDeClasse extends Model
{

    protected $fillable = [
        'name',
        'capacity',
        'formation_id',
        'code',
    ];
    public function formation()
    {
        return $this->belongsTo(Niveauformation::class , 'formation_id');
    }
}
