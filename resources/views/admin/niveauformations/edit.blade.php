@extends('layouts.masters.master')

@section('title')
    Modifier le niveau de formation
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-school"></i>
            </span>
            Modifier le niveau de formation
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('web.dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.niveauformations.index') }}">Niveaux de formation</a></li>
                <li class="breadcrumb-item active">Modifier</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informations du niveau</h4>
                    <form method="POST" action="{{ route('web.niveauformations.update', $niveauformation->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nom">Nom <span class="text-danger">*</span></label>
                            <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom', $niveauformation->nom) }}" required>
                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ordre">Ordre <span class="text-danger">*</span></label>
                            <input id="ordre" type="number" class="form-control @error('ordre') is-invalid @enderror" name="ordre" value="{{ old('ordre', $niveauformation->ordre) }}" required>
                            @error('ordre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('web.niveauformations.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-cancel"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
