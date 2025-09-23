<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anneescolaire extends Model
{
    protected $fillable = ['annee','date_debut','date_fin', 'is_active'];
}
