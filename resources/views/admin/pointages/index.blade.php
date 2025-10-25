@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.pointages') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-clock-check"></i>
            </span>
            {{ __('pointages.liste_pointages') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('messages.dashboard') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('pointages.pointages') }}</li>
            </ul>
        </nav>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle-outline icon-lg me-3"></i>
                        <div>
                            <h4 class="font-weight-normal mb-1">{{ $stats['presents'] }}</h4>
                            <p class="card-text">{{ __('pointages.presents_seulement') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-close-circle-outline icon-lg me-3"></i>
                        <div>
                            <h4 class="font-weight-normal mb-1">{{ $stats['absents'] }}</h4>
                            <p class="card-text">{{ __('pointages.absents_seulement') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-clipboard-check-outline icon-lg me-3"></i>
                        <div>
                            <h4 class="font-weight-normal mb-1">{{ $stats['total'] }}</h4>
                            <p class="card-text">{{ __('pointages.total_pointages') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <!-- Barre d'actions -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ __('pointages.liste_pointages') }}</h4>
                        <div class="btn-group">
                            <a href="{{ route('web.pointages.create') }}" class="btn btn-gradient-primary btn-sm">
                                <i class="mdi mdi-plus"></i> {{ __('pointages.nouveau_pointage') }}
                            </a>
                            <a href="{{ route('web.pointages.rapide') }}" class="btn btn-gradient-success btn-sm">
                                <i class="mdi mdi-clock-fast"></i> {{ __('pointages.pointage_rapide') }}
                            </a>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('web.pointages.index') }}" class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">{{ __('pointages.filtrer') }}</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('pointages.professeur') }}</label>
                                            <select name="teacher_id" class="form-select">
                                                <option value="">{{ __('pointages.tous_professeurs') }}</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}"
                                                        {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('pointages.date_debut') }}</label>
                                            <input type="date" name="date_debut" class="form-control"
                                                   value="{{ request('date_debut') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('pointages.date_fin') }}</label>
                                            <input type="date" name="date_fin" class="form-control"
                                                   value="{{ request('date_fin') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('pointages.statut') }}</label>
                                            <select name="statut" class="form-select">
                                                <option value="">{{ __('pointages.tous_statuts') }}</option>
                                                <option value="present" {{ request('statut') == 'present' ? 'selected' : '' }}>
                                                    {{ __('pointages.present') }}
                                                </option>
                                                <option value="absent" {{ request('statut') == 'absent' ? 'selected' : '' }}>
                                                    {{ __('pointages.absent') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-gradient-primary">
                                                    <i class="mdi mdi-filter"></i> {{ __('pointages.filtrer') }}
                                                </button>
                                                <a href="{{ route('web.pointages.index') }}" class="btn btn-light">
                                                    <i class="mdi mdi-refresh"></i> {{ __('pointages.reinitialiser') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des pointages -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('pointages.date_pointage') }}</th>
                                    <th>{{ __('pointages.professeur') }}</th>
                                    <th>{{ __('pointages.cours') }}</th>
                                    <th>{{ __('pointages.classe') }}</th>
                                    <th>{{ __('pointages.horaires') }}</th>
                                    <th>{{ __('pointages.statut') }}</th>
                                    <th>{{ __('pointages.heure_arrivee') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointages as $pointage)
                                    <tr>
                                        <td>{{ $pointage->date_pointage->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-account-circle me-2"></i>
                                                {{ $pointage->teacher->name }}
                                            </div>
                                        </td>
                                        <td>{{ $pointage->emploiTemps->subject->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-gradient-info">
                                                {{ $pointage->emploiTemps->classe->nom ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($pointage->emploiTemps->horairess->count() > 0)
                                                <small>
                                                    {{ $pointage->emploiTemps->horairess->pluck('libelle_fr')->join(', ') }}
                                                </small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($pointage->statut == 'present')
                                                <span class="badge badge-gradient-success">
                                                    <i class="mdi mdi-check-circle"></i>
                                                    {{ __('pointages.present') }}
                                                </span>
                                            @else
                                                <span class="badge badge-gradient-danger">
                                                    <i class="mdi mdi-close-circle"></i>
                                                    {{ __('pointages.absent') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $pointage->heure_arrivee ? $pointage->heure_arrivee->format('H:i') : '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('web.pointages.show', $pointage->id) }}"
                                                   class="btn btn-sm btn-gradient-info" title="{{ __('messages.view') }}">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('web.pointages.edit', $pointage->id) }}"
                                                   class="btn btn-sm btn-gradient-warning" title="{{ __('messages.edit') }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('web.pointages.destroy', $pointage->id) }}"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('{{ __('pointages.confirmer_suppression') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-gradient-danger"
                                                            title="{{ __('messages.delete') }}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="mdi mdi-clock-outline display-4 text-muted"></i>
                                                <p class="mt-2 text-muted">{{ __('pointages.aucun_pointage') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($pointages->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pointages->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.75em;
}
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.icon-lg {
    font-size: 2.5rem;
}
</style>
@endpush
