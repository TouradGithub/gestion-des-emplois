@extends('layouts.masters.master')

@section('title', 'Créer un trimestre')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer un trimestre</h3>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('web.trimesters.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du trimestre</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="niveau_pedagogique_id" class="form-label">Niveau pédagogique</label>
                        <select name="niveau_pedagogique_id" id="niveau_pedagogique_id" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($niveaux as $niveau)
                                <option value="{{ $niveau->id }}">{{ $niveau->nom }}  ({{ optional($niveau->formation)->nom }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
