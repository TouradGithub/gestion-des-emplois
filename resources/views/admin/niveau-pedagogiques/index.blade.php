@extends('layouts.masters.master')

@section('title')
    Liste des Niveaux Pédagogiques
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des Niveaux Pédagogiques</h3>
            <a href="{{ route('web.niveau-pedagogiques.create') }}" class="btn btn-success btn-sm">Ajouter</a>
        </div>

        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Ordre</th>
                        <th>Formation</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($niveaux as $niveau)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $niveau->nom }}</td>
                            <td>{{ $niveau->ordre }}</td>
                            <td>{{ $niveau->formation->nom ?? '-' }}</td>
                            <td>
                                <a href="{{ route('web.niveau-pedagogiques.edit', $niveau->id) }}" class="btn btn-primary btn-sm">Modifier</a>
                                <form method="POST" action="{{ route('web.niveau-pedagogiques.destroy', $niveau->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($niveaux->isEmpty())
                        <tr><td colspan="5" class="text-center">Aucun niveau disponible.</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
