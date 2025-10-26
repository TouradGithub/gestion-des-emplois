@extends('layouts.masters.master')

@section('title', 'Modifier le trimestre')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Modifier le trimestre</h3>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('web.trimesters.update', $trimester->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du trimestre</label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="{{ old('name', $trimester->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="niveau_pedagogique_id" class="form-label">Niveau pédagogique</label>
                        <select name="niveau_pedagogique_id" id="niveau_pedagogique_id" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($niveaux as $niveau)
                                <option value="{{ $niveau->id }}"
                                        {{ old('niveau_pedagogique_id', $trimester->niveau_pedagogique_id) == $niveau->id ? 'selected' : '' }}>
                                    {{ $niveau->nom }} ({{ optional($niveau->formation)->nom }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('web.trimesters.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
