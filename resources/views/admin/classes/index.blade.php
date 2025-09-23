@extends('layouts.masters.master')

@section('title', 'Liste des Classes')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Classes</h3>
        </div>

        <a href="{{ route('web.classes.create') }}" class="btn btn-primary mb-3">Ajouter Classe</a>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Niveau</th>
                <th>Spécialité</th>
                <th>Année</th>
                <th>Emplois</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($classes as $classe)
                <tr>
                    <td>{{ $classe->nom }}</td>
                    <td>{{ $classe->niveau->nom }}</td>
                    <td>{{ $classe->specialite->name }}</td>
                    <td>{{ $classe->annee->annee }}</td>
                    <td>
                        @if($classe->emplois)
                            <a href="{{ route('web.emplois.showEmploi', $classe->id) }}" class="btn btn-info">
                                Voir Emploi
                            </a>
                        @else
                            -
                        @endif
                        <a href="{{route('web.classes.delete' , $classe->id )}}" class="btn btn-danger">
                            <i class="fa fa-trash "></i></a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
