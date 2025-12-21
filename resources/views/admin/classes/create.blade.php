@extends('layouts.masters.master')

@section('title', 'Créer Classe')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer une nouvelle classe</h3>
        </div>
        @if($errors->all())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <form action="{{ route('web.classes.store') }}" method="POST">
            @csrf
{{--            <div class="form-group">--}}
{{--                <label>Nom</label>--}}
{{--                <input type="text" name="nom" class="form-control" required>--}}
{{--            </div>--}}

            <div class="form-group">
                <label>Niveau Pédagogique</label>
                <select name="niveau_pedagogique_id" class="form-control" required>
                    <option value="">Choisir...</option>
                    @foreach ($niveaux as $niveau)
                        <option value="{{ $niveau->id }}">{{ $niveau->nom . '  (' . $niveau->formation->nom.')' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Spécialité</label>
                <select name="specialite_id" class="form-control" required>
                    <option value="">Choisir...</option>
                    @foreach ($specialites as $specialite)
                        <option value="{{ $specialite->id }}">{{ $specialite->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Année</label>
                <select name="annee_id" class="form-control" required>
                    <option value="">Choisir...</option>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}" @if($annee->is_active == 1)selected @endif>{{ $annee->annee }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label><i class="mdi mdi-account-group"></i> {{ __('messages.capacity') }}</label>
                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
                       value="{{ old('capacity', 20) }}" min="1" max="500">
                <small class="text-muted">{{ __('messages.class_capacity') }}</small>
                @error('capacity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
        </form>
    </div>
@endsection
