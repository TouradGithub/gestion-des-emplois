<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmploiTemps  extends Model
{

    protected $table = 'emplois_temps';

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'trimester_id',
        'annee_id',
        'jour_id',
        'salle_de_classe_id',
    ];
    public function ref_horaires(): BelongsToMany
    {
        return $this->belongsToMany(Horaire::class, 'emploi_horaire', 'emploi_temps_id', 'horaire_id');
    }
    public function horaires()
    {
        return $this->belongsToMany(EmploiHoraire::class, 'emploi_horaire', 'emploi_temps_id', 'horaire_id');
    }
    public function horairess()
    {
        return $this->belongsToMany(Horaire::class, 'emploi_horaire', 'emploi_temps_id', 'horaire_id');
    }
    public function classe()      {
        return $this->belongsTo(Classe::class, 'class_id');
    }
    public function subject()     { return $this->belongsTo(Subject::class); }
    public function teacher()     { return $this->belongsTo(Teacher::class); }
    public function trimester()   { return $this->belongsTo(Trimester::class); }
    public function annee()       { return $this->belongsTo(Anneescolaire::class, 'annee_id'); }
    public function jour()        {
        return $this->belongsTo(Jour::class ,'jour_id');
    }
    public function salle()       { return $this->belongsTo(SalleDeClasse::class, 'salle_de_classe_id'); }

    /**
     * Relation avec les pointages
     */
    public function pointages()
    {
        return $this->hasMany(Pointage::class, 'emploi_temps_id');
    }

    public function getHoraires(): array
    {
        if ($this->ref_horaires->count() == 1) {
            return [$this->ref_horaires->first()->start_time, $this->ref_horaires->first()->end_time];
        } else {
            if ($this->ref_horaires->count() == 0) {
                return ['00:00:00', '00:00:00'];
            }
            $first = $this->ref_horaires()->first()->start_time;
            $last = $this->ref_horaires()->first()->end_time;
            return [$first, $last];
        }

    }


}
