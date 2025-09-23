
@extends('layouts.masters.master')

@section('title')
    {{ __('Créer Salle de Classe') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer Salle de Classe</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3" action="{{ route('web.salle-de-classes.store') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Nom --}}
                                <div class="form-group col-md-4">
                                    <label>Nom<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="Nom" value="{{ old('name') }}" >
                                </div>

                                {{-- Numéro de Salle --}}
                                <div class="form-group col-md-4">
                                    <label>Numéro de Salle<span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control"
                                           placeholder="code de Salle" value="{{ old('code') }}" required>
                                </div>

                                {{-- Nombre d'étudiants   --}}
                                <div class="form-group col-md-4">
                                    <label>Nombre d'étudiants<span class="text-danger">*</span></label>
                                    <input type="number" name="capacity" class="form-control"
                                           placeholder="Nombre d'étudiants" value="{{ old('capacity') }}" required>
                                </div>

                                {{-- Formation --}}
                                <div class="form-group col-md-4">
                                    <label>Formation<span class="text-danger">*</span></label>
                                    <select name="formation_id" class="form-control" >
                                        <option value="">-- Choisir --</option>
                                        @foreach($formations as $formation)
                                            <option value="{{ $formation->id }}"
                                                {{ old('formation_id') == $formation->id ? 'selected' : '' }}>
                                                {{ $formation->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-theme mt-3">Enregistrer</button>
                            <a href="{{ route('web.salle-de-classes.index') }}" class="btn btn-light mt-3">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
