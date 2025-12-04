
{{-- <x-card> --}}
<x-modal.modal-header-body>
    <x-slot name="title">
        @lang('scolarite::emplois_temps.add_emplois_temps')
    </x-slot>
    <div class="row">
        <div class="col-md-12" id="EditEmForm">
            <form class="" action="{{ url('scolarites/emplois_temps/edit') }}" method="post">
                @csrf
                <div class="form-row">
                    @if(!auth()->user()->b_etablissement)
                        <div class="col-md-6">
                            <x-forms.select
                                label="{{trans('scolarite::emplois_temps.etablissement')}}"
                                id="etablissement_id"
                                class="select2"
                                name="etablissement"
                                onchange="getEtablissementClassePointeurs(this.value)"
                                :disabled="$has_pointage?true:false"
                            >
                                <option value="">{!! trans('scolarite::emplois_temps.tous') !!}</option>
                                @foreach($etablissements as $etablissement)
                                    <option
                                        @if($etablissement->id == $etablissement_id)
                                            selected
                                        @endif
                                    value="{{$etablissement->id}}">{{$etablissement?->libelle}}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                    @endif
                    <div class="col-md-6">
                            <x-forms.select
                                label="{{trans('scolarite::emplois_temps.classe')}}"
                                field-required
                                data-placeholder="{!! trans('text.select') !!}"
                                class="select2"
                                name="classe"
                                id="classe_clander_edit_id"
                                onchange="getClasseSalles(this.value)"
                                :disabled="$has_pointage?true:false"
                            >
                                <option value=""></option>
                                @foreach($classes as $classe)
                                    <option
                                        @if($classe->id==$emplois_temp->classe_id)
                                            selected
                                        @endif
                                        value="{{$classe->id}}">{{$classe->libelle_fr}}</option>
                                @endforeach
                            </x-forms.select>
                    </div>
                    <div class="col-md-6">
                            <x-forms.select
                                label="{!! trans('scolarite::emplois_temps.horaire') !!}"
                                class="select2"
                                name="horaire[]"
                                field-required
                                multiple
                                :disabled="$has_pointage?true:false"
                                onchange="getSalleFormateursDisponibleThisHoraire()"
                            >
                                @foreach($horaires->sortBy('ordre') as $heure)
                                    <option
                                        @if(in_array($heure->id, $emplois_temp->sct_ref_horaires->pluck('id')->toArray()))
                                            selected
                                        @endif
                                        value="{{$heure->id}}">{{$heure->libelle}}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                        <div class="col-md-6 mb-0">
                            <x-forms.input
                                type="date"
                                label="{!! trans('scolarite::emplois_temps.date_debuit') !!}"
                                field-required
                                value="{{$emplois_temp?->date_debut?->format('Y-m-d')}}"
                                name="date_debut"
                                id="date_debut_edit_em"
                                :readonly="$has_pointage?true:false"
                                onchange="getSalleFormateursDisponibleThisHoraire()"
                            />
                            <small class="text-danger error-date_debut mt-0"></small>
                        </div>
                    <div class="col-md-6 mb-0">
                        <x-forms.input
                            type="date"
                            label="{!! trans('text.date_fin') !!}"
                            field-required
                            value="{{ $emplois_temp?->date_fin?->format('Y-m-d') }}"
                            name="date_fin"
                            {{-- onchange="getSalleFormateursDisponibleThisHoraire()" --}}
                            {{-- :readonly="$has_pointage?true:false" --}}
                        />
                        <small class="text-danger error-date_fin mt-0"></small>
                    </div>
                    <div class="col-md-6">
                            <x-forms.select
                                data-placeholder="{!! trans('text.select') !!}"
                                label="{!! trans('scolarite::emplois_temps.jour') !!}"
                                class="select2"
                                name="jour"
                                field-required
                                :disabled="$has_pointage?true:false"
                                onchange="getSalleFormateursDisponibleThisHoraire()"
                            >
                                <option value=""></option>
                                @foreach($jours as $jour)
                                    <option
                                        @if($jour->id==$emplois_temp->sct_ref_jour_id)
                                            selected
                                        @endif
                                        value="{{$jour->id}}">{{$jour->libelle}}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                    <div class="col-md-6" id="classe_matiere_edit_cnt">
                    </div>
                    <div class="col-md-6" id="classe_salle_edit_cnt">
                    </div>
                    <div class="col-md-6" id="classe_formateur_edit_cnt">
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
                                <option
                                    @if($pointeur->id==$emplois_temp->pointeur_id)
                                        selected
                                    @endif
                                    value="{{$pointeur->id}}">
                                        {{$pointeur->prenom.''.$pointeur->nom}}
                                </option>
                            @endforeach
                        </x-forms.select>
                    </div>
                        <div class="col-md-6">
                            <x-forms.input
                                type="number"
                                label="{!! trans('scolarite::emplois_temps.nbr_pointage') !!}"
                                value="{{ $emplois_temp->nbr_pointage }}"
                                name="nbr_pointage"
                            />
                        </div>

                </div>
                <input type="hidden" name="id" id="emploi_id" value="{{$emplois_temp->id}}">
                <input type="hidden" name="has_pointage" value="{{ $has_pointage }}"/>
            </form>
            <div class="form-row mt-1">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end"> <!-- Flexbox for right alignment on the same line -->
                        @if(!$has_pointage)
                            <button class="btn btn-danger mr-1" id="delete_emploi_temps"
                                onclick="confirmAndRefreshDT('{{ url('scolarites/emplois_temps/delete/' . $emplois_temp->id) }}','{{trans('text.confirm_suppression')}}',()=>{
                                $('.close').click();
                                refreshCalandar()})"
                            >
                                <i class="fa fa-times"></i>
                            {{ trans('text.supprimer') }}
                            </button>
                        @endif
                        <x-buttons.btn-save
                            onclick="saveform(this, () => {
                                $('.close').click();
                                refreshCalandar();
                            })"
                            container="EditEmForm">
                            @lang('text.enregistrer')
                        </x-buttons.btn-save>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-modal.modal-header-body>
<script>
    $(Document).ready(function(){
            $('.select2').select2(
                {
                    width: '100%',
                    placeholder: $(this).data('placeholder'),
                }
            );
            if(($('#classe_clander_edit_id').val()) != ''){
                getClasseSalles($('#classe_clander_edit_id').val());
            }
            if(($('#matiere_id').val()) != ''){
                getClasseMatiereFormateurs($('#matiere_id').val());
            }else{
                $('#classe_formateur_edit_cnt').html('');
            }
        })
        function getClasseSalles(id) {
            $('#classe_salle_edit_cnt').html('');
            $('#classe_matiere_edit_cnt').html('');
            let emploi_id = $('#emploi_id').val();
            getTheContent('scolarites/emplois_temps/getClasseSalles/'+id+'/'+emploi_id, '#classe_salle_edit_cnt',
                getClasseMatieres(id)
            );
        }
        function getClasseMatieres(id) {
            $('#classe_formateur_edit_cnt').html('');
            let emploi_id = $('#emploi_id').val();
            getTheContent('scolarites/emplois_temps/getClasseMatieres/'+id+'/'+emploi_id, '#classe_matiere_edit_cnt');
        }
        function getClasseMatiereFormateurs(id) {
            let classe_id = $('#classe_clander_edit_id').val();
            let emploi_id = $('#emploi_id').val();
            getTheContent('scolarites/emplois_temps/getClasseMatiereFormateurs/'+id+'/'+classe_id+'/'+emploi_id, '#classe_formateur_edit_cnt');
        }
        function getEtablissementClassePointeurs(id) {
            $('#classe_salle_edit_cnt').html('');
            $('#classe_matiere_edit_cnt').html('');
            $('#classe_formateur_edit_cnt').html('');
            $.ajax({
                    url: racine + 'scolarites/emplois_temps/getEtablissementClassePointeurs/' + id,
                    success: function(data) {
                        $('#classe_clander_edit_id').html(data.classes);
                        $('#classe_clander_edit_id').select2();
                        $('#pointeurs').html(data.pointeurs);
                        $('#pointeurs').select2();
                        getClasseSalles($('#classe_clander_edit_id').val());
                    }
                })
        }
        function getSalleFormateursDisponibleThisHoraire() {
            let horaireIds = Array.from(document.querySelector('select[name="horaire[]"]')?.selectedOptions || [])
            .map(option => option.value);
            let classe_ids = $('#select_classe').val()??$('#classes').val();
            $('#classe_formateur_edit_cnt').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
            $('#classe_salle_edit_cnt').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
            $.ajax({
                url: racine + 'scolarites/emplois_temps/getSalleFormateursDisponibleThisHoraire/',
                type: 'GET',
                data: {
                    horaire_ids: horaireIds,
                    classe_ids: $('select[name="classe"]').val(),
                    date_debut: $('#date_debut_edit_em').val(),
                    date_fin: $('input[name="date_fin"]').val(),
                    jour: $('select[name="jour"]').val(),
                    emploi_id: $('#emploi_id').val()
                },
                success: function(data) {
                    // alert(JSON.stringify(data));
                    $('#classe_salle_edit_cnt').html(data.salles_html);
                    $('#classe_formateur_edit_cnt').html(data.formateurs_html);
                    $('.select2').select2({
                        width: '100%',
                        dropdownAutoWidth: true
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        // loop through each field error
                        $.each(errors, function (key, messages) {
                            $('.error-' + key).text(messages[0]);
                        });
                    }
                    $('#classe_salle_edit_cnt').html('');
                    $('#classe_formateur_edit_cnt').html('');
                }
            })
        }
</script>

