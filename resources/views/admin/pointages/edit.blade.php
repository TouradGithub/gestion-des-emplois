@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.modifier_pointage') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-warning text-white me-2">
                <i class="mdi mdi-pencil"></i>
            </span>
            {{ __('pointages.modifier_pointage') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('messages.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.pointages.index') }}">{{ __('pointages.pointages') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('pointages.modifier') }}</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">{{ __('pointages.modifier_pointage') }}</h4>
                            <p class="card-description text-muted">
                                {{ __('pointages.modifier_description') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge badge-gradient-info fs-6 p-2">
                                <i class="mdi mdi-calendar-today"></i>
                                {{ \Carbon\Carbon::parse($pointage->date_pointage)->locale(app()->getLocale())->isoFormat('dddd, LL') }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations actuelles -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('pointages.informations_actuelles') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ __('pointages.professeur') }}:</strong>
                                    <br>
                                    <i class="mdi mdi-account-circle text-primary"></i>
                                    {{ $pointage->emploiTemps->teacher->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('pointages.classe') }}:</strong>
                                    <br>
                                    <span class="badge badge-gradient-info">
                                        {{ $pointage->emploiTemps->classe->nom }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>{{ __('pointages.matiere') }}:</strong>
                                    <br>{{ $pointage->emploiTemps->subject->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ __('pointages.horaires') }}:</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $pointage->emploiTemps->horairess->pluck('libelle_fr')->join(', ') }}
                                    </small>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <strong>{{ __('pointages.statut_actuel') }}:</strong>
                                    <br>
                                    @if($pointage->statut === 'present')
                                        <span class="badge badge-success">
                                            <i class="mdi mdi-check-circle"></i>
                                            {{ __('pointages.present') }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="mdi mdi-close-circle"></i>
                                            {{ __('pointages.absent') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification -->
                    <form method="POST" action="{{ route('web.pointages.update', $pointage->id) }}" id="editPointageForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Sélection du cours (en lecture seule mais modifiable si nécessaire) -->
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">
                                    <i class="mdi mdi-account text-primary"></i>
                                    {{ __('pointages.professeur') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror"
                                        id="teacher_id" name="teacher_id" required>
                                    <option value="">{{ __('pointages.choisir_professeur') }}</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                                {{ $pointage->emploiTemps->teacher_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date du pointage -->
                            <div class="col-md-6 mb-3">
                                <label for="date_pointage" class="form-label">
                                    <i class="mdi mdi-calendar text-primary"></i>
                                    {{ __('pointages.date') }} <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control @error('date_pointage') is-invalid @enderror"
                                       id="date_pointage"
                                       name="date_pointage"
                                       value="{{ old('date_pointage', $pointage->date_pointage) }}"
                                       required>
                                @error('date_pointage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sélection du cours -->
                            <div class="col-md-12 mb-3">
                                <label for="emploi_temps_id" class="form-label">
                                    <i class="mdi mdi-book-open-page-variant text-primary"></i>
                                    {{ __('pointages.cours') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('emploi_temps_id') is-invalid @enderror"
                                        id="emploi_temps_id" name="emploi_temps_id" required>
                                    <option value="{{ $pointage->emploi_temps_id }}" selected>
                                        {{ $pointage->emploiTemps->classe->nom }} -
                                        {{ $pointage->emploiTemps->subject->name }}
                                        ({{ $pointage->emploiTemps->horairess->pluck('libelle_fr')->join(', ') }})
                                    </option>
                                </select>
                                @error('emploi_temps_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    {{ __('pointages.cours_aide_modification') }}
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label">
                                    <i class="mdi mdi-check-circle-outline text-primary"></i>
                                    {{ __('pointages.statut') }} <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group d-flex" role="group" aria-label="Statut pointage">
                                    <input type="radio"
                                           class="btn-check"
                                           name="statut"
                                           id="present"
                                           value="present"
                                           {{ old('statut', $pointage->statut) == 'present' ? 'checked' : '' }}
                                           required>
                                    <label class="btn btn-outline-success" for="present">
                                        <i class="mdi mdi-check-circle me-1"></i>
                                        {{ __('pointages.present') }}
                                    </label>

                                    <input type="radio"
                                           class="btn-check"
                                           name="statut"
                                           id="absent"
                                           value="absent"
                                           {{ old('statut', $pointage->statut) == 'absent' ? 'checked' : '' }}
                                           required>
                                    <label class="btn btn-outline-danger" for="absent">
                                        <i class="mdi mdi-close-circle me-1"></i>
                                        {{ __('pointages.absent') }}
                                    </label>
                                </div>
                                @error('statut')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commentaires (optionnel) -->
                            <div class="col-md-12 mb-3">
                                <label for="commentaires" class="form-label">
                                    <i class="mdi mdi-note-text text-primary"></i>
                                    {{ __('pointages.commentaires') }}
                                </label>
                                <textarea class="form-control @error('commentaires') is-invalid @enderror"
                                          id="commentaires"
                                          name="commentaires"
                                          rows="3"
                                          placeholder="{{ __('pointages.commentaires_placeholder') }}">{{ old('commentaires', $pointage->commentaires) }}</textarea>
                                @error('commentaires')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    {{ __('pointages.commentaires_aide') }}
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('web.pointages.index') }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left"></i>
                                    {{ __('messages.cancel') }}
                                </a>
                                <a href="{{ route('web.pointages.show', $pointage->id) }}" class="btn btn-outline-info">
                                    <i class="mdi mdi-eye"></i>
                                    {{ __('pointages.voir_details') }}
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-gradient-warning">
                                    <i class="mdi mdi-content-save"></i>
                                    {{ __('pointages.modifier') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Historique des modifications -->
            @if($pointage->updated_at != $pointage->created_at)
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="mdi mdi-history text-info"></i>
                            {{ __('pointages.historique_modifications') }}
                        </h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ __('pointages.creation_initiale') }}</h6>
                                    <p class="text-muted mb-0">
                                        {{ $pointage->created_at->locale(app()->getLocale())->diffForHumans() }}
                                        ({{ $pointage->created_at->format('d/m/Y H:i') }})
                                    </p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ __('pointages.derniere_modification') }}</h6>
                                    <p class="text-muted mb-0">
                                        {{ $pointage->updated_at->locale(app()->getLocale())->diffForHumans() }}
                                        ({{ $pointage->updated_at->format('d/m/Y H:i') }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const dateInput = document.getElementById('date_pointage');
    const coursSelect = document.getElementById('emploi_temps_id');
    const form = document.getElementById('editPointageForm');

    // Fonction pour charger les cours disponibles
    function chargerCours() {
        const teacherId = teacherSelect.value;
        const date = dateInput.value;

        if (teacherId && date) {
            // Montrer un indicateur de chargement
            coursSelect.disabled = true;
            coursSelect.innerHTML = '<option value="">{{ __("pointages.chargement_cours") }}</option>';

            // Appel AJAX pour récupérer les cours
            fetch(`{{ route('web.pointages.get-emplois') }}?teacher_id=${teacherId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    coursSelect.innerHTML = '<option value="">{{ __("pointages.choisir_cours") }}</option>';

                    if (data.length === 0) {
                        coursSelect.innerHTML = '<option value="">{{ __("pointages.aucun_cours_disponible") }}</option>';
                    } else {
                        data.forEach(emploi => {
                            const option = document.createElement('option');
                            option.value = emploi.id;
                            option.textContent = `${emploi.classe_nom} - ${emploi.subject_name} (${emploi.horaires})`;

                            // Sélectionner l'option actuelle
                            if (emploi.id == {{ $pointage->emploi_temps_id }}) {
                                option.selected = true;
                            }

                            coursSelect.appendChild(option);
                        });
                    }

                    coursSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    coursSelect.innerHTML = '<option value="">{{ __("pointages.erreur_chargement_cours") }}</option>';
                    coursSelect.disabled = false;
                });
        } else {
            coursSelect.innerHTML = '<option value="">{{ __("pointages.selectionner_professeur_date") }}</option>';
            coursSelect.disabled = true;
        }
    }

    // Event listeners
    teacherSelect.addEventListener('change', chargerCours);
    dateInput.addEventListener('change', chargerCours);

    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const statutRadios = document.querySelectorAll('input[name="statut"]');
        const statutSelected = Array.from(statutRadios).some(radio => radio.checked);

        if (!statutSelected) {
            e.preventDefault();
            alert('{{ __("pointages.validation_statut_requis") }}');
            return false;
        }

        if (!coursSelect.value) {
            e.preventDefault();
            alert('{{ __("pointages.validation_cours_requis") }}');
            return false;
        }
    });

    // Animation des boutons radio
    const statutRadios = document.querySelectorAll('input[name="statut"]');
    statutRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Effet visuel lors du changement
            this.parentElement.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.parentElement.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.5em 0.75em;
}

.btn-check:checked + .btn {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1rem;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 1rem;
    width: 2px;
    height: calc(100% - 1rem);
    background: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content h6 {
    color: #495057;
    font-size: 0.875rem;
}

.form-text {
    font-size: 0.8rem;
    color: #6c757d;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}
</style>
@endpush
