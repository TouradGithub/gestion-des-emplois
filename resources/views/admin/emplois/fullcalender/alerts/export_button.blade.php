<?php
use Illuminate\Support\Facades\Crypt;
use Dcs\Scolarite\Models\PrPeFormateur;
    $formateur=PrPeFormateur::where('user_id',\auth()->user()->id)->first();
 ?>
<div class="d-flex justify-content-between w-100">
<div> <p>{!! trans('scolarite::emplois_temps.nbr_heurs_planifier_pour_cette_classe') !!} : <b>
    {{
     $classe?->emplois_temps()?->where('actif',1)
    ->when(\auth()->user()->sys_types_user_id == 3, function ($query) use ($formateur) {
        $query->where('formateur_id', $formateur->id);
    })
    ->withCount('sct_ref_horaires')->get()->sum('sct_ref_horaires_count') }}</b></p>
</div>

@if($classe?->emplois_temps?->count()>0)
    <div>
        <a
            href="{{url('scolarites/emplois_temps/classe_emplois_temps_export' , Crypt::encrypt($classe->id))}}"
            class="btn btn-sm btn-secondary" target="_blank">
            <i class="fas fa-file-pdf"></i>
            {{-- trans --}}
            {{ trans('scolarite::emplois_temps.emplois_temps') }}
        </a>
        <a
            href="{{$url = url('scolarites/emplois_temps/classe_emplois_temps_export/'.Crypt::encrypt($classe->id).'?excel=1')}}"
            class="btn btn-sm btn-primary ml-1" target="_blank">
            <i class="fas fa-file-excel"></i>
            {{ trans('scolarite::emplois_temps.emplois_temps') }}
        </a>
    </div>
@endif
</div>
