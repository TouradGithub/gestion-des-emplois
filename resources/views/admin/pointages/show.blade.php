@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.details_pointage') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="mdi mdi-eye"></i>
            </span>
            {{ __('pointages.details_pointage') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('messages.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.pointages.index') }}">{{ __('pointages.pointages') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('pointages.details') }}</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Carte principale des détails -->
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="mdi mdi-information-outline me-2"></i>
                            {{ __('pointages.informations_pointage') }}
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('web.pointages.edit', $pointage->id) }}"
                               class="btn btn-light btn-sm">
                                <i class="mdi mdi-pencil"></i>
                                {{ __('pointages.modifier') }}
                            </a>
                            <button type="button"
                                    class="btn btn-outline-light btn-sm"
                                    onclick="window.print()">
                                <i class="mdi mdi-printer"></i>
                                {{ __('pointages.imprimer') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statut principal -->
                        <div class="col-12 mb-4">
                            <div class="text-center py-3">
                                @if($pointage->statut === 'present')
                                    <div class="display-4 text-success mb-3">
                                        <i class="mdi mdi-check-circle"></i>
                                    </div>
                                    <h4 class="text-success mb-2">{{ __('pointages.present') }}</h4>
                                    <p class="text-muted">{{ __('pointages.professeur_present_description') }}</p>
                                @else
                                    <div class="display-4 text-danger mb-3">
                                        <i class="mdi mdi-close-circle"></i>
                                    </div>
                                    <h4 class="text-danger mb-2">{{ __('pointages.absent') }}</h4>
                                    <p class="text-muted">{{ __('pointages.professeur_absent_description') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Informations détaillées -->
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="mdi mdi-account-circle text-primary me-2"></i>
                                    {{ __('pointages.professeur') }}
                                </label>
                                <div class="info-value">
                                    <strong>{{ $pointage->emploiTemps->teacher->name }}</strong>
                                    @if($pointage->emploiTemps->teacher->email)
                                        <br><small class="text-muted">
                                            <i class="mdi mdi-email"></i>
                                            {{ $pointage->emploiTemps->teacher->email }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="mdi mdi-calendar text-primary me-2"></i>
                                    {{ __('pointages.date') }}
                                </label>
                                <div class="info-value">
                                    <strong>{{ \Carbon\Carbon::parse($pointage->date_pointage)->locale(app()->getLocale())->isoFormat('dddd, LL') }}</strong>
                                    <br><small class="text-muted">
                                        {{ $pointage->date_pointage ? \Carbon\Carbon::parse($pointage->date_pointage)->diffForHumans() : __('messages.unknown') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="mdi mdi-school text-primary me-2"></i>
                                    {{ __('pointages.classe') }}
                                </label>
                                <div class="info-value">
                                    <span class="badge badge-gradient-info fs-6 p-2">
                                        {{ $pointage->emploiTemps->classe->nom }}
                                    </span>
                                    @if($pointage->emploiTemps->classe->specialite)
                                        <br><small class="text-muted">
                                            {{ $pointage->emploiTemps->classe->specialite->nom }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="mdi mdi-book-open-page-variant text-primary me-2"></i>
                                    {{ __('pointages.matiere') }}
                                </label>
                                <div class="info-value">
                                    <strong>{{ $pointage->emploiTemps->subject->name }}</strong>
                                    @if($pointage->emploiTemps->subject->description)
                                        <br><small class="text-muted">
                                            {{ $pointage->emploiTemps->subject->description }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="mdi mdi-clock-outline text-primary me-2"></i>
                                    {{ __('pointages.horaires') }}
                                </label>
                                <div class="info-value">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($pointage->emploiTemps->horairess as $horaire)
                                            <span class="badge badge-outline-dark">
                                                <i class="mdi mdi-clock"></i>
                                                {{ $horaire->libelle_fr }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($pointage->commentaires)
                            <div class="col-md-12 mb-3">
                                <div class="info-item">
                                    <label class="info-label">
                                        <i class="mdi mdi-note-text text-primary me-2"></i>
                                        {{ __('pointages.commentaires') }}
                                    </label>
                                    <div class="info-value">
                                        <div class="alert alert-light">
                                            <i class="mdi mdi-quote-left text-muted"></i>
                                            {{ $pointage->commentaires }}
                                            <i class="mdi mdi-quote-right text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="mdi mdi-information text-info me-2"></i>
                        {{ __('pointages.informations_systeme') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">{{ __('pointages.date_creation') }}</small>
                            <br>
                            <strong>{{ $pointage->created_at->locale(app()->getLocale())->isoFormat('dddd, LL [à] LT') }}</strong>
                            <br>
                            <span class="text-muted">{{ $pointage->created_at ? $pointage->created_at->diffForHumans() : __('messages.unknown') }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">{{ __('pointages.derniere_modification') }}</small>
                            <br>
                            <strong>{{ $pointage->updated_at->locale(app()->getLocale())->isoFormat('dddd, LL [à] LT') }}</strong>
                            <br>
                            <span class="text-muted">{{ $pointage->updated_at ? $pointage->updated_at->diffForHumans() : __('messages.unknown') }}</span>
                        </div>
                    </div>

                    @if($pointage->created_at != $pointage->updated_at)
                        <div class="alert alert-info mt-3">
                            <i class="mdi mdi-information"></i>
                            {{ __('pointages.pointage_modifie_info') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques connexes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="mdi mdi-chart-line text-success me-2"></i>
                        {{ __('pointages.statistiques_connexes') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="text-primary mb-1">{{ $statsProf['total_cours_mois'] }}</h4>
                                <small class="text-muted">{{ __('pointages.cours_ce_mois') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="text-success mb-1">{{ $statsProf['presences_mois'] }}</h4>
                                <small class="text-muted">{{ __('pointages.presences_mois') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="text-danger mb-1">{{ $statsProf['absences_mois'] }}</h4>
                                <small class="text-muted">{{ __('pointages.absences_mois') }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h4 class="text-warning mb-1">{{ $statsProf['taux_presence'] }}%</h4>
                                <small class="text-muted">{{ __('pointages.taux_presence') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('web.pointages.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left"></i>
                                {{ __('pointages.retour_liste') }}
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('web.pointages.edit', $pointage->id) }}"
                               class="btn btn-gradient-warning">
                                <i class="mdi mdi-pencil"></i>
                                {{ __('pointages.modifier') }}
                            </a>

                            <button type="button"
                                    class="btn btn-gradient-danger"
                                    onclick="confirmDelete({{ $pointage->id }})">
                                <i class="mdi mdi-delete"></i>
                                {{ __('pointages.supprimer') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="mdi mdi-alert-circle text-warning me-2"></i>
                    {{ __('pointages.confirmer_suppression') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="mdi mdi-delete-alert display-1 text-danger mb-3"></i>
                    <h6>{{ __('pointages.suppression_irreversible') }}</h6>
                    <p class="text-muted">
                        {{ __('pointages.confirmer_suppression_description') }}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    {{ __('messages.cancel') }}
                </button>
                <form method="POST" action="{{ route('web.pointages.destroy', $pointage->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-gradient-danger">
                        <i class="mdi mdi-delete"></i>
                        {{ __('pointages.supprimer_definitif') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonction de confirmation de suppression
function confirmDelete(pointageId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Fonction d'impression
window.addEventListener('beforeprint', function() {
    // Cacher les boutons et éléments non nécessaires à l'impression
    document.querySelectorAll('.btn, .breadcrumb, .card-header .btn-group').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Réafficher les éléments après impression
    document.querySelectorAll('.btn, .breadcrumb, .card-header .btn-group').forEach(el => {
        el.style.display = '';
    });
});

// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 0.75rem;
    border: none;
}

.card-header {
    border-radius: 0.75rem 0.75rem 0 0 !important;
    border-bottom: none;
}

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    color: #212529;
}

.badge.fs-6 {
    font-size: 1rem !important;
    padding: 0.5em 0.75em;
}

.display-4 {
    font-size: 4rem;
}

.stat-card {
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
    margin-bottom: 1rem;
}

.stat-card h4 {
    font-weight: 700;
    font-size: 1.75rem;
}

.alert-light {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

@media print {
    .content-wrapper {
        padding: 0 !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .page-header,
    .btn,
    .breadcrumb,
    .card-header .btn-group {
        display: none !important;
    }

    .badge {
        color: #000 !important;
        background-color: #f8f9fa !important;
        border: 1px solid #ddd !important;
    }
}

.badge-outline-dark {
    color: #495057;
    border: 1px solid #495057;
    background-color: transparent;
}

.modal-content {
    border-radius: 0.75rem;
}

.display-1 {
    font-size: 6rem;
}
</style>
@endpush
