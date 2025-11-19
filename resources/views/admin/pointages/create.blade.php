@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.nouveau_pointage') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-plus-circle"></i>
            </span>
            {{ __('pointages.nouveau_pointage') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ __('messages.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('web.pointages.index') }}">{{ __('pointages.pointages') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('pointages.nouveau_pointage') }}</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('pointages.ajouter_pointage') }}</h4>
                    <p class="card-description">{{ __('pointages.aide_pointage') }}</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('web.pointages.store') }}" id="pointageForm">
                        @csrf

                        <div class="row">
                            <!-- Date de pointage -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_pointage" class="form-label">
                                        {{ __('pointages.date_pointage') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control @error('date_pointage') is-invalid @enderror"
                                           id="date_pointage"
                                           name="date_pointage"
                                           value="{{ old('date_pointage', $today) }}"
                                           required>
                                    @error('date_pointage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Professeur -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id" class="form-label">
                                        {{ __('pointages.professeur') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id"
                                            required>
                                        <option value="">{{ __('Sélectionner un professeur') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Emploi du temps -->
                        <div class="form-group">
                            <label for="emploi_temps_id" class="form-label">
                                {{ __('pointages.cours') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('emploi_temps_id') is-invalid @enderror"
                                    id="emploi_temps_id"
                                    name="emploi_temps_id"
                                    required
                                    disabled>
                                <option value="">{{ __('Sélectionner d\'abord un professeur et une date') }}</option>
                            </select>
                            @error('emploi_temps_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        {{ __('pointages.statut_presence') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-check-container d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input "
                                                   type="radio"
                                                   name="statut"
                                                   id="statut_present"
                                                   value="present"
                                                   {{ old('statut') == 'present' ? 'checked' : '' }}>
                                            <label class="form-check-label text-success" for="statut_present">
                                                <i class="mdi mdi-check-circle"></i>
                                                {{ __('pointages.present') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="statut"
                                                   id="statut_absent"
                                                   value="absent"
                                                   {{ old('statut') == 'absent' ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger" for="statut_absent">
                                                <i class="mdi mdi-close-circle"></i>
                                                {{ __('pointages.absent') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('statut')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Heure d'arrivée -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="heure_arrivee" class="form-label">
                                        {{ __('pointages.heure_arrivee') }}
                                    </label>
                                    <input type="time"
                                           class="form-control @error('heure_arrivee') is-invalid @enderror"
                                           id="heure_arrivee"
                                           name="heure_arrivee"
                                           value="{{ old('heure_arrivee') }}">
                                    <small class="form-text text-muted">{{ __('pointages.aide_heure') }}</small>
                                    @error('heure_arrivee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Heure de départ -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="heure_depart" class="form-label">
                                        {{ __('pointages.heure_depart') }}
                                    </label>
                                    <input type="time"
                                           class="form-control @error('heure_depart') is-invalid @enderror"
                                           id="heure_depart"
                                           name="heure_depart"
                                           value="{{ old('heure_depart') }}">
                                    <small class="form-text text-muted">{{ __('pointages.aide_heure') }}</small>
                                    @error('heure_depart')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Remarques -->
                        <div class="form-group">
                            <label for="remarques" class="form-label">{{ __('pointages.remarques') }}</label>
                            <textarea class="form-control @error('remarques') is-invalid @enderror"
                                      id="remarques"
                                      name="remarques"
                                      rows="3"
                                      placeholder="{{ __('pointages.aide_remarques') }}">{{ old('remarques') }}</textarea>
                            @error('remarques')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('web.pointages.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left"></i>
                                {{ __('pointages.annuler_retour') }}
                            </a>
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="mdi mdi-content-save"></i>
                                {{ __('pointages.enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panneau d'information -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('pointages.information') }}</h4>

                    <div class="alert alert-info">
                        <h6><i class="mdi mdi-information-outline"></i> {{ __('pointages.instructions') }}</h6>
                        <ul class="mb-0 small">
                            <li>{{ __('pointages.selectionner_date_professeur') }}</li>
                            <li>{{ __('pointages.cours_chargement_automatique') }}</li>
                            <li>{{ __('pointages.marquer_presence_absence') }}</li>
                            <li>{{ __('pointages.heures_optionnelles') }}</li>
                        </ul>
                    </div>

                    <div id="cours-info" class="mt-3" style="display: none;">
                        <h6>{{ __('pointages.informations_cours') }}</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-1"><strong>{{ __('pointages.classe') }}:</strong> <span id="info-classe"></span></p>
                                <p class="mb-1"><strong>{{ __('pointages.matiere') }}:</strong> <span id="info-matiere"></span></p>
                                <p class="mb-1"><strong>{{ __('pointages.horaires') }}:</strong> <span id="info-horaires"></span></p>
                                <p class="mb-0"><strong>{{ __('pointages.jour') }}:</strong> <span id="info-jour"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const dateInput = document.getElementById('date_pointage');
    const emploiSelect = document.getElementById('emploi_temps_id');
    const coursInfo = document.getElementById('cours-info');

    function loadEmploisForTeacher() {

        const teacherId = teacherSelect.value;
        const date = dateInput.value;
     

        if (!teacherId || !date) {
            emploiSelect.disabled = true;
            emploiSelect.innerHTML = '<option value="">{{ __("Sélectionner d\'abord un professeur et une date") }}</option>';
            coursInfo.style.display = 'none';
            return;
        }

        // Afficher un indicateur de chargement
        emploiSelect.innerHTML = '<option value="">{{ __("messages.loading") }}</option>';

        fetch(`{{ route('web.pointages.get-emplois') }}?teacher_id=${teacherId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                emploiSelect.innerHTML = '<option value="">{{ __("Sélectionner un cours") }}</option>';

                if (data.emplois.length > 0) {
                    data.emplois.forEach(emploi => {
                        const option = document.createElement('option');
                        option.value = emploi.id;
                        option.textContent = emploi.display;
                        option.dataset.classe = emploi.classe_nom;
                        option.dataset.matiere = emploi.subject_nom;
                        option.dataset.horaires = emploi.horaires;
                        option.dataset.jour = emploi.jour_nom;
                        emploiSelect.appendChild(option);
                    });
                    emploiSelect.disabled = false;
                } else {
                    emploiSelect.innerHTML = '<option value="">{{ __("Aucun cours trouvé pour ce jour") }}</option>';
                    emploiSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                emploiSelect.innerHTML = '<option value="">{{ __("Erreur de chargement") }}</option>';
                emploiSelect.disabled = true;
            });
    }

    function updateCoursInfo() {
        const selectedOption = emploiSelect.options[emploiSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
            document.getElementById('info-classe').textContent = selectedOption.dataset.classe || '-';
            document.getElementById('info-matiere').textContent = selectedOption.dataset.matiere || '-';
            document.getElementById('info-horaires').textContent = selectedOption.dataset.horaires || '-';
            document.getElementById('info-jour').textContent = selectedOption.dataset.jour || '-';
            coursInfo.style.display = 'block';
        } else {
            coursInfo.style.display = 'none';
        }
    }

    // Event listeners
    teacherSelect.addEventListener('change', loadEmploisForTeacher);
    dateInput.addEventListener('change', loadEmploisForTeacher);
    emploiSelect.addEventListener('change', updateCoursInfo);

    // Auto-activer les champs heure selon le statut
    const statutRadios = document.querySelectorAll('input[name="statut"]');
    const heureArrivee = document.getElementById('heure_arrivee');
    const heureDepart = document.getElementById('heure_depart');

    statutRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'present') {
                heureArrivee.disabled = false;
                heureDepart.disabled = false;
                // Auto-remplir l'heure d'arrivée avec l'heure actuelle si vide
                if (!heureArrivee.value) {
                    const now = new Date();
                    heureArrivee.value = now.getHours().toString().padStart(2, '0') + ':' +
                                        now.getMinutes().toString().padStart(2, '0');
                }
            } else {
                heureArrivee.disabled = true;
                heureDepart.disabled = true;
                heureArrivee.value = '';
                heureDepart.value = '';
            }
        });
    });

    // Charger les emplois si des valeurs sont déjà sélectionnées (cas de validation échouée)
    if (teacherSelect.value && dateInput.value) {
        loadEmploisForTeacher();
    }
});
</script>
@endSection

@push('styles')
<style>
.form-check-container {
    align-items: center;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 5px;
}

.form-check-label {
    font-weight: 500;
    cursor: pointer;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert {
    border-radius: 8px;
}

#cours-info .card {
    border: none;
}

.text-danger {
    color: #dc3545 !important;
}

.text-success {
    color: #28a745 !important;
}
</style>
@endpush
