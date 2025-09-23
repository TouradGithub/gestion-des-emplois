@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.specialities') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer une spécialité</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('web.specialities.store') }}" method="POST" novalidate>
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Nom <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Département <span class="text-danger">*</span></label>
                                <select name="departement_id" class="form-control" required>
                                    <option value="">{{ __('genirale.choose') }}</option>
                                    @foreach($departements as $departement)
                                        <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                            {{ $departement->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="niveau_formation_id">Niveau <span class="text-danger">*</span></label>
                                <select name="niveau_formation_id" class="form-control" required>
                                    <option value="">-- Choisir --</option>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}" {{ old('niveau_formation_id') == $niveau->id ? 'selected' : '' }}>
                                            {{ $niveau->nom }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>


                            <button type="submit" class="btn btn-theme">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
