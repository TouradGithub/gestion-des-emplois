<?php
    use Carbon\Carbon;
 $days=['Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>7];
 $frenchDays = ['Mon' => 'Lundi','Tue' => 'Mardi','Wed' => 'Mercredi','Thu' => 'Jeudi','Fri' => 'Vendredi','Sat' => 'Samedi','Sun' => 'Dimanche'];
?>
<x-modal.modal-header-body>
    <x-slot name="title">
        @lang('scolarite::emplois_temps.add_emplois_temps')
        <h3>: {{ $classe->libelle_fr }} - {{ $frenchDays[$day]}}</h3>
    </x-slot>
        <div class="row">
            <div class="col-md-12" id="addEmForm">
                <form class="" action="{{ url('scolarites/emplois_temps/add') }}" method="post">
                    @csrf
                    <div class="form-row">
                        @if($classe!=null)
                            <input type="hidden" name="classe[]" id="select_classe" value="{{ $classe->id }}">
                        @else
                            <div class="col-md-6">
                                <x-forms.select
                                    label="{!! trans('scolarite::emplois_temps.classe') !!}"
                                    class="select2"
                                    id="classes"
                                    name="classe[]"
                                    field-required
                                    multiple
                                    onchange="getClasseSalles(this.value)"
                                >
                                    @foreach($classes as $classe)
                                        <option
                                            @if(in_array($classe->id,$classes_ids))
                                                selected
                                            @endif
                                            value="{{$classe->id}}">{{$classe->libelle_fr." /".$classe->niveau_pedagogique->niveau."-".$classe->niveau_pedagogique->niveaux_formation->libelle."-".$classe->niveau_pedagogique->specialite->libelle}}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <x-forms.input
                                type="date"
                                label="{!! trans('scolarite::emplois_temps.date_debuit') !!}"
                                field-required
                                {{-- readonly --}}
                                value="{{$date}}"
                                name="date_debut"
                                id="date_debut_em"
                                onchange="getSalleFormateursDisponibleThisHoraire()"
                            />
                        </div>
                            <?php
                            $currentDate = new DateTime();
                                $targetMonth = 6;
                                if ($currentDate->format('m') > $targetMonth) {
                                    $targetYear = $currentDate->format('Y') + 1;
                                } else {
                                    $targetYear = $currentDate->format('Y');
                                }
                                $dateFin = new DateTime("$targetYear-06-01");
                            ?>
                            <div class="col-md-6">
                                <x-forms.input
                                    type="date"
                                    label="{!! trans('text.date_fin') !!}"
                                    field-required
                                    value="{{ $dateFin->format('Y-m-d') }}"
                                    name="date_fin"
                                    onchange="getSalleFormateursDisponibleThisHoraire()"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-forms.select
                                    label="{!! trans('scolarite::emplois_temps.horaire') !!}"
                                    class="select2"
                                    name="horaire[]"
                                    field-required
                                    multiple
                                    onchange="getSalleFormateursDisponibleThisHoraire()"
                                >
                                    @foreach($horaires->sortBy('ordre') as $heure)
                                        <option
                                            @if(in_array($heure->id, $horaires_ids))
                                                selected
                                            @endif
                                            value="{{$heure->id}}">{{$heure->libelle}}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="col-md-6" id="classe_matiere_cnt">

                            </div>
                            <div class="col-md-6" id="classe_salle_cnt">

                            </div>
                            <div class="col-md-6" id="classe_formateur_cnt">
                                <x-forms.select
                                    label="{{trans('scolarite::emplois_temps.formateur')}}"
                                    name="formateur"
                                    data-placeholder="{{trans('text.select')}}"
                                    field-required
                                    class="select2"
                                    >
                                    <option value=""></option>
                                    @foreach($formateurs as $formateur)
                                        <option value="{{$formateur->id}}">{{$formateur->full_name}}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        <div class="col-md-6">
                            <x-forms.select
                                label="{!! trans('scolarite::emplois_temps.pointeur') !!}"
                                class="select2"
                                name="pointeur"
                                id="pointeurs"
                            >
                                <option value="">{!! trans('scolarite::emplois_temps.tous') !!}</option>
                                @foreach($pointeurs as $pointeur)
                                    <option value="{{$pointeur->id}}">{{$pointeur->prenom.' '.$pointeur->nom}}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                            <div class="col-md-6">
                                <x-forms.input
                                    type="number"
                                    label="{!! trans('scolarite::emplois_temps.nbr_pointage') !!}"
                                    value="1"
                                    name="nbr_pointage"
                                />
                            </div>

                             <input type="hidden" name="jour" value="{{ $days[$day] }}">
                            {{-- <div class="col-md-6">
                                <x-forms.select
                                    label="{!! trans('scolarite::emplois_temps.horaire') !!}"
                                    class="select2"
                                    name="horaire[]"
                                    field-required
                                    multiple
                                >
                                    @foreach($horaires->sortBy('ordre') as $heure)
                                        <option
                                            @if(in_array($heure->id, $horaires_ids))
                                                selected
                                            @endif
                                            value="{{$heure->id}}">{{$heure->libelle}}</option>
                                    @endforeach
                                </x-forms.select>
                            </div> --}}
                    </div>
                </form>
                <div class="form-row mt-1">
                    <div class="col-md-12">
                        <div class="text-right">
                            <x-buttons.btn-save
                                onclick="saveform(this, ()=>{
                                    $('.close').click();
                                    refreshCalandar();
                                    })"
                                container="addEmForm">
                                @lang('text.ajouter')
                            </x-buttons.btn-save>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-modal.modal-header-body>
<script>
    function getClasseSalles(value) {
        let selectedValues = Array.from(document.querySelector('select[name="classe[]"]')?.selectedOptions || [])
        .map(option => option.value);
        if ($('#select_classe').val()??false) {
            selectedValues = $('#select_classe').val();
        }
        getTheContent('scolarites/emplois_temps/getClasseSalles/'+selectedValues, '#classe_salle_cnt',
           getClasseMatieres(selectedValues)
        );
    }

    function getClasseMatieres(id) {
        // $('#classe_formateur_cnt').html('');
        getTheContent('scolarites/emplois_temps/getClasseMatieres/'+id, '#classe_matiere_cnt');
    }

    function getClasseMatiereFormateurs(id) {
        let selectedValues = Array.from(document.querySelector('select[name="classe[]"]')?.selectedOptions || [])
        .map(option => option.value);

        if ($('#select_classe').val()??false) {
            selectedValues = $('#select_classe').val();
        }

        getTheContent('scolarites/emplois_temps/getClasseMatiereFormateurs/'+id+'/'+selectedValues, '#classe_formateur_cnt');
    }

    $(document).ready(function () {
        $('.select2').select2({
            width: '100%'
        });
        // alert($('#select_classe').val())
        if($('#select_classe').val()){
            getClasseSalles($('#select_classe').val());
            getClasseMatieres($('#select_classe').val());
            getSalleFormateursDisponibleThisHoraire()
        }

    });
    function getEtablissementClassePointeurs(id) {
        $('#classe_salle_cnt').html('');
        $('#classe_matiere_cnt').html('');
        $('#classe_formateur_cnt').html('');
        $.ajax({
            url: racine + 'scolarites/emplois_temps/getEtablissementClassePointeurs/' + id,
            success: function(data) {
                $('#classes').html(data.classes);
                $('#classes').select2();
                $('#pointeurs').html(data.pointeurs);
                $('#pointeurs').select2();
            }
        })
    }
    function getSalleFormateursDisponibleThisHoraire() {
        let horaireIds = Array.from(document.querySelector('select[name="horaire[]"]')?.selectedOptions || [])
        .map(option => option.value);
        let classe_ids = $('#select_classe').val()??$('#classes').val();
        $('#classe_formateur_cnt').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        $('#classe_salle_cnt').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
        $.ajax({
            url: racine + 'scolarites/emplois_temps/getSalleFormateursDisponibleThisHoraire/',
            type: 'GET',
            data: {
                horaire_ids: horaireIds,
                classe_ids: $('#select_classe').val()??$('#classes').val(),
                date_debut: $('#date_debut_em').val(),
                date_fin: $('input[name="date_fin"]').val(),
                jour: '{{ $days[$day] }}'
            },
            success: function(data) {
                // alert(JSON.stringify(data));
                $('#classe_salle_cnt').html(data.salles_html);
                $('#classe_formateur_cnt').html(data.formateurs_html);
                $('.select2').select2({
                    width: '100%',
                    dropdownAutoWidth: true
                });
            }
        })
    }
</script>
