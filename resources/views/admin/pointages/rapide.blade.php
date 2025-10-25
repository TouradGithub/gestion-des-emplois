@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.pointage_rapide') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-clock-fast"></i>
            </span>
            {{ __('pointages.pointage_rapide') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('messages.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.pointages.index') }}">{{ __('pointages.pointages') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('pointages.pointage_rapide') }}</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">{{ __('pointages.pointage_rapide') }}</h4>
                            <p class="card-description text-muted">
                                {{ __('pointages.marquer_presence_absence_tous') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="badge badge-gradient-info fs-6 p-2">
                                <i class="mdi mdi-calendar-today"></i>
                                {{ \Carbon\Carbon::parse($today)->locale(app()->getLocale())->isoFormat('dddd, LL') }}
                            </div>
                        </div>
                    </div>

                    @if($emploisAujourdhui->isEmpty())
                        <div class="text-center py-5">
                            <i class="mdi mdi-calendar-remove display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">{{ __('pointages.aucun_cours_programme') }}</h5>
                            <a href="{{ route('web.pointages.index') }}" class="btn btn-gradient-primary mt-3">
                                <i class="mdi mdi-arrow-left"></i>
                                {{ __('pointages.retour_liste') }}
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('web.pointages.store-rapide') }}" id="pointageRapideForm">
                            @csrf
                            <input type="hidden" name="date_pointage" value="{{ $today }}">

                            <!-- Actions en haut -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm" onclick="marquerTousPresents()">
                                        <i class="mdi mdi-check-all"></i>
                                        {{ __('pointages.tous_presents') }}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="marquerTousAbsents()">
                                        <i class="mdi mdi-close-circle-multiple"></i>
                                        {{ __('pointages.tous_absents') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="reinitialiser()">
                                        <i class="mdi mdi-refresh"></i>
                                        {{ __('pointages.reinitialiser') }}
                                    </button>
                                </div>

                                <div>
                                    <span class="badge badge-light me-2">
                                        {{ $emploisAujourdhui->count() }} {{ __('pointages.cours_au_total') }}
                                    </span>
                                    <span class="badge badge-warning">
                                        <span id="count-deja-enregistres">{{ count($pointagesExistants) }}</span>
                                        {{ __('pointages.deja_enregistres') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Liste des cours -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 20%">{{ __('pointages.professeur') }}</th>
                                            <th style="width: 15%">{{ __('pointages.classe') }}</th>
                                            <th style="width: 20%">{{ __('pointages.matiere') }}</th>
                                            <th style="width: 15%">{{ __('pointages.horaires') }}</th>
                                            <th style="width: 15%">{{ __('pointages.statut') }}</th>
                                            <th style="width: 10%">{{ __('pointages.statut_actuel') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($emploisAujourdhui->groupBy('teacher_id') as $teacherId => $emploisProf)
                                            @foreach($emploisProf as $index => $emploi)
                                                @php
                                                    $dejaEnregistre = in_array($emploi->id, $pointagesExistants);
                                                    $globalIndex = $loop->parent->index * 100 + $loop->index;
                                                @endphp
                                                <tr class="{{ $dejaEnregistre ? 'table-warning' : '' }}"
                                                    data-emploi-id="{{ $emploi->id }}">
                                                    <td>{{ $globalIndex + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="mdi mdi-account-circle me-2 text-primary"></i>
                                                            <div>
                                                                <strong>{{ $emploi->teacher->name }}</strong>
                                                                @if($index == 0 && $emploisProf->count() > 1)
                                                                    <br><small class="text-muted">
                                                                        {{ $emploisProf->count() }} cours aujourd'hui
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-gradient-info">
                                                            {{ $emploi->classe->nom }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $emploi->subject->name }}</td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $emploi->horairess->pluck('libelle_fr')->join(', ') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($dejaEnregistre)
                                                            <span class="badge badge-warning">
                                                                <i class="mdi mdi-check-circle"></i>
                                                                {{ __('Déjà enregistré') }}
                                                            </span>
                                                        @else
                                                            <input type="hidden"
                                                                   name="pointages[{{ $globalIndex }}][emploi_temps_id]"
                                                                   value="{{ $emploi->id }}">

                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <input type="radio"
                                                                       class="btn-check"
                                                                       name="pointages[{{ $globalIndex }}][statut]"
                                                                       id="present_{{ $emploi->id }}"
                                                                       value="present"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-success"
                                                                       for="present_{{ $emploi->id }}">
                                                                    <i class="mdi mdi-check"></i>
                                                                    {{ __('pointages.present') }}
                                                                </label>

                                                                <input type="radio"
                                                                       class="btn-check"
                                                                       name="pointages[{{ $globalIndex }}][statut]"
                                                                       id="absent_{{ $emploi->id }}"
                                                                       value="absent"
                                                                       autocomplete="off">
                                                                <label class="btn btn-outline-danger"
                                                                       for="absent_{{ $emploi->id }}">
                                                                    <i class="mdi mdi-close"></i>
                                                                    {{ __('pointages.absent') }}
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($dejaEnregistre)
                                                            <i class="mdi mdi-check-circle text-success"></i>
                                                        @else
                                                            <i class="mdi mdi-clock-outline text-muted"
                                                               id="status_{{ $emploi->id }}"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Résumé et boutons d'action -->
                            <div class="card bg-light mt-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-2">{{ __('pointages.resume') }}</h6>
                                            <div class="d-flex gap-3">
                                                <span class="badge badge-success fs-6">
                                                    <span id="count-presents">0</span> {{ __('pointages.present') }}
                                                </span>
                                                <span class="badge badge-danger fs-6">
                                                    <span id="count-absents">0</span> {{ __('pointages.absent') }}
                                                </span>
                                                <span class="badge badge-secondary fs-6">
                                                    <span id="count-non-marques">{{ $emploisAujourdhui->count() - count($pointagesExistants) }}</span>
                                                    {{ __('pointages.non_marques') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="{{ route('web.pointages.index') }}" class="btn btn-light me-2">
                                                <i class="mdi mdi-arrow-left"></i>
                                                {{ __('pointages.annuler_retour') }}
                                            </a>
                                            <button type="submit" class="btn btn-gradient-primary">
                                                <i class="mdi mdi-content-save"></i>
                                                {{ __('pointages.enregistrer') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour marquer tous comme présents
    window.marquerTousPresents = function() {
        const presentRadios = document.querySelectorAll('input[type="radio"][value="present"]');
        presentRadios.forEach(radio => {
            if (!radio.disabled) {
                radio.checked = true;
                updateStatus(radio);
            }
        });
        updateCounts();
    };

    // Fonction pour marquer tous comme absents
    window.marquerTousAbsents = function() {
        const absentRadios = document.querySelectorAll('input[type="radio"][value="absent"]');
        absentRadios.forEach(radio => {
            if (!radio.disabled) {
                radio.checked = true;
                updateStatus(radio);
            }
        });
        updateCounts();
    };

    // Fonction pour réinitialiser
    window.reinitialiser = function() {
        const allRadios = document.querySelectorAll('input[type="radio"]');
        allRadios.forEach(radio => {
            if (!radio.disabled) {
                radio.checked = false;
                updateStatus(radio);
            }
        });
        updateCounts();
    };

    // Fonction pour mettre à jour le statut visuel
    function updateStatus(radio) {
        const emploiId = radio.name.match(/\[(\d+)\]/)[1];
        const statusIcon = document.getElementById(`status_${radio.value === 'present' ? emploiId : emploiId}`);

        if (statusIcon) {
            const row = radio.closest('tr');
            if (radio.checked) {
                if (radio.value === 'present') {
                    statusIcon.className = 'mdi mdi-check-circle text-success';
                    row.classList.add('table-success');
                    row.classList.remove('table-danger');
                } else {
                    statusIcon.className = 'mdi mdi-close-circle text-danger';
                    row.classList.add('table-danger');
                    row.classList.remove('table-success');
                }
            } else {
                statusIcon.className = 'mdi mdi-clock-outline text-muted';
                row.classList.remove('table-success', 'table-danger');
            }
        }
    }

    // Fonction pour mettre à jour les compteurs
    function updateCounts() {
        const presentsCount = document.querySelectorAll('input[type="radio"][value="present"]:checked').length;
        const absentsCount = document.querySelectorAll('input[type="radio"][value="absent"]:checked').length;
        const totalPossible = {{ $emploisAujourdhui->count() - count($pointagesExistants) }};
        const nonMarques = totalPossible - presentsCount - absentsCount;

        document.getElementById('count-presents').textContent = presentsCount;
        document.getElementById('count-absents').textContent = absentsCount;
        document.getElementById('count-non-marques').textContent = nonMarques;
    }

    // Event listeners pour tous les radios
    const allRadios = document.querySelectorAll('input[type="radio"]');
    allRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateStatus(this);
            updateCounts();
        });
    });

    // Validation avant soumission
    document.getElementById('pointageRapideForm').addEventListener('submit', function(e) {
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        if (checkedRadios.length === 0) {
            e.preventDefault();
            alert('{{ __("Veuillez marquer au moins un pointage avant d\'enregistrer") }}');
            return false;
        }
    });

    // Initialiser les compteurs
    updateCounts();
});
</script>
@endpush

@push('styles')
<style>
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.btn-group-sm .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5em 0.75em;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.table-success {
    background-color: rgba(40, 167, 69, 0.1);
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-check:checked + .btn {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.display-1 {
    font-size: 6rem;
}
</style>
@endpush
