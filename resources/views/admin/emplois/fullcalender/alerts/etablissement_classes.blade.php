<x-forms.select
    label="{{trans('scolarite::emplois_temps.classe')}}"
    required="true"
    id="classes_filter_select"
    class="select2"
    name="classe"
    onchange="getExportButton(this.value)"
>
    <option value="">@lang('text.all')</option>
    @foreach($classes as $classe)
        <option value="{{$classe->id}}">{{$classe->libelle_fr}}</option>
    @endforeach
</x-forms.select>