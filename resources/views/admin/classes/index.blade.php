@extends('layouts.masters.master')

@section('title', 'Liste des Classes')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-school"></i>
                </span>
                Classes
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Classes</li>
                </ul>
            </nav>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('web.classes.create') }}" class="btn btn-gradient-primary">
                <i class="mdi mdi-plus"></i> Ajouter Classe
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                        <tr>
                            <th><i class="mdi mdi-account-group"></i> Nom</th>
                            <th><i class="mdi mdi-signal-variant"></i> Niveau</th>
                            <th><i class="mdi mdi-book-open-variant"></i> Spécialité</th>
                            <th><i class="mdi mdi-calendar"></i> Année</th>
                            <th><i class="mdi mdi-cog"></i> Actions</th>
                        </tr>
                        </thead>
            <tbody>
            @foreach ($classes as $classe)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-account-group me-2 text-primary"></i>
                            <strong>{{ $classe->nom }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-gradient-info">{{ $classe->niveau->nom }}</span>
                    </td>
                    <td>{{ $classe->specialite->name }}</td>
                    <td>
                        <i class="mdi mdi-calendar me-1"></i>{{ $classe->annee->annee }}
                    </td>
                    <td>
                        @if($classe->emplois)
                            <a href="{{ route('web.emplois.showEmploi', $classe->id) }}" class="btn btn-info btn-sm">
                                <i class="mdi mdi-calendar-clock"></i> Voir Emploi
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                        <a href="{{route('web.classes.delete' , $classe->id )}}" class="btn btn-danger btn-sm ms-1">
                            <i class="mdi mdi-delete"></i>
                        </a>

                    </td>
                </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
