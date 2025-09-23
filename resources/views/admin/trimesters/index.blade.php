@extends('layouts.masters.master')

@section('title', 'Liste des trimestres')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des trimestres</h3>
        </div>

        <div class="mb-3 text-end">
            <a href="{{ route('web.trimesters.create') }}" class="btn btn-primary">Ajouter un trimestre</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Niveau pédagogique</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trimesters as $trimester)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trimester->name }}</td>
                            <td>
{{--                                {{ $trimester->niveau->nom ($trimester->niveau->formation->nom ) }}--}}
                                {{ optional($trimester->niveau)->nom }} ({{ optional(optional($trimester->niveau)->formation)->nom }})

                                {{--                                {{ $trimester?->niveau->id  ($trimester->niveau)}}--}}

                            </td>
                            <td>
                                <a href="{{ route('web.trimesters.edit', $trimester) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('web.trimesters.destroy', $trimester) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce trimestre?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
