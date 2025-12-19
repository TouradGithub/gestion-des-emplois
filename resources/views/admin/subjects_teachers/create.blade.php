@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.teachers') }}
@endsection

@section('css')
<style>
    .page-header-custom {
        background:  #06a465;
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 25px;
        color: #fff;
    }
    .page-header-custom h3 {
        margin: 0;
        font-weight: 700;
    }
    .page-header-custom p {
        margin: 5px 0 0 0;
        opacity: 0.8;
    }
    .main-card {
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
    }
    .main-card .card-body {
        padding: 30px;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
    }
    .section-title i {
        color: #1a1a1a;
        margin-right: 8px;
    }
    .form-group label {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .hours-input-group {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        border: 2px solid #e0e0e0;
    }
    .hours-input-group .input-group-text {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 10px 0 0 10px;
    }
    .hours-input-group .form-control {
        border-radius: 0 10px 10px 0;
        font-size: 1.2rem;
        font-weight: 600;
        text-align: center;
    }
    .hours-info {
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        border: 1px solid #e0e0e0;
    }
    .hours-info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .hours-info-item:last-child {
        border-bottom: none;
    }
    .hours-info-item .label {
        color: #666;
    }
    .hours-info-item .value {
        font-weight: 600;
        color: #1a1a1a;
    }
    .btn-submit {
        background:  #06a465;
        border: none;
        color: #fff;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        background: #333;
        color: #fff;
    }
    .btn-back {
        background: #fff;
        border: 2px solid #1a1a1a;
        color: #1a1a1a;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-back:hover {
        background: #1a1a1a;
        color: #fff;
    }
    .actions-row {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        margin-top: 25px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><i class="mdi mdi-account-plus me-2"></i> Affecter Professeur à Matière</h3>
                <p>Créer une nouvelle affectation enseignant-matière-classe</p>
            </div>
            <a href="{{ route('web.subjects_teachers.index') }}" class="btn btn-light">
                <i class="mdi mdi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card main-card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-alert-circle me-2"></i>Erreurs détectées:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('web.subjects_teachers.store') }}" method="POST" id="assignmentForm">
                        @csrf

                        <!-- Section: Informations de base -->
                        <div class="section-title">
                            <i class="mdi mdi-account-tie"></i> Informations de l'affectation
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="mdi mdi-account me-1"></i> Professeur <span class="text-danger">*</span></label>
                                    <select name="teacher_id" id="teacher_id" class="form-select" required>
                                        <option value="">-- Sélectionner un professeur --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="mdi mdi-book-open-variant me-1"></i> Matières <span class="text-danger">*</span></label>
                                    <select name="subject_ids[]" id="subject_ids" class="form-select" multiple required style="height: 120px;">
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ is_array(old('subject_ids')) && in_array($subject->id, old('subject_ids')) ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Maintenez Ctrl pour sélectionner plusieurs matières
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="mdi mdi-account-group me-1"></i> Classe <span class="text-danger">*</span></label>
                                    <select name="class_id" id="class_id" class="form-select" required>
                                        <option value="">-- Sélectionner une classe --</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('class_id') == $classe->id ? 'selected' : '' }}>
                                                {{ $classe->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="mdi mdi-calendar-range me-1"></i> Trimestre <span class="text-danger">*</span></label>
                                    <select name="trimester_id" id="trimester_id" class="form-select" required>
                                        <option value="">-- Sélectionner un trimestre --</option>
                                        @foreach($trimesters as $trimester)
                                            <option value="{{ $trimester->id }}" {{ old('trimester_id') == $trimester->id ? 'selected' : '' }}>
                                                {{ $trimester->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Heures du trimestre -->
                        <div class="section-title mt-4">
                            <i class="mdi mdi-clock-outline"></i> Volume horaire du trimestre
                        </div>

                        <div class="hours-input-group">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="mb-2"><i class="mdi mdi-clock-time-four me-1"></i> Total heures du trimestre <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-timer"></i>
                                        </span>
                                        <input type="number"
                                               class="form-control"
                                               id="heures_trimestre"
                                               name="heures_trimestre"
                                               min="0"
                                               max="500"
                                               step="0.5"
                                               value="{{ old('heures_trimestre', 0) }}"
                                               placeholder="Ex: 48"
                                               required>
                                        <span class="input-group-text" style="background: #fff; border: 2px solid #e0e0e0; border-left: none; border-radius: 0 10px 10px 0;">
                                            h/trimestre
                                        </span>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Définissez le nombre total d'heures que cet enseignant doit effectuer pendant tout le trimestre
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <div class="hours-info">
                                        <h6 class="mb-3"><i class="mdi mdi-calculator me-2"></i> Calcul automatique</h6>
                                        <div class="hours-info-item">
                                            <span class="label">Heures/semaine (÷12)</span>
                                            <span class="value" id="heures_semaine_calc">0 h</span>
                                        </div>
                                        <div class="hours-info-item">
                                            <span class="label">Heures/mois (÷3)</span>
                                            <span class="value" id="heures_mois">0 h</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="actions-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('web.subjects_teachers.index') }}" class="btn btn-back">
                                    <i class="mdi mdi-close me-2"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-submit">
                                    <i class="mdi mdi-content-save me-2"></i> Enregistrer l'affectation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <div class="card main-card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="mdi mdi-information me-2"></i> Instructions
                    </h5>

                    <div class="alert" style="background: #f5f5f5; border: 1px solid #e0e0e0; color: #1a1a1a;">
                        <i class="mdi mdi-lightbulb-on me-2"></i>
                        <strong>Comment procéder :</strong>
                        <ul class="mb-0 mt-2">
                            <li>Sélectionnez le professeur</li>
                            <li>Choisissez une ou plusieurs matières (Ctrl+clic)</li>
                            <li>Sélectionnez la classe</li>
                            <li>Choisissez le trimestre</li>
                            <li><strong>Définissez les heures totales du trimestre</strong></li>
                        </ul>
                    </div>

                    <div class="alert mt-3" style="background: #e8f5e9; border: 1px solid #4caf50; color: #2e7d32;">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <strong>Note :</strong>
                        <p class="mb-0 mt-2 small">
                            Un professeur peut enseigner plusieurs matières dans la même classe.
                            Les affectations existantes seront ignorées automatiquement.
                        </p>
                    </div>

                    <div class="alert mt-3" style="background: #fff; border: 2px solid #1a1a1a; color: #1a1a1a;">
                        <i class="mdi mdi-clock-alert me-2"></i>
                        <strong>Volume horaire :</strong>
                        <p class="mb-0 mt-2 small">
                            Le volume horaire du trimestre représente le nombre total d'heures que l'enseignant doit effectuer
                            pendant toute la durée du trimestre pour cette matière dans cette classe.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Calculate weekly and monthly hours from trimester total
    function calculateHours() {
        var heuresTrimestre = parseFloat($('#heures_trimestre').val()) || 0;
        var heuresSemaine = (heuresTrimestre / 12).toFixed(1);
        var heuresMois = (heuresTrimestre / 3).toFixed(1);

        $('#heures_semaine_calc').text(heuresSemaine + ' h');
        $('#heures_mois').text(heuresMois + ' h');
    }

    // Update on input change
    $('#heures_trimestre').on('input', calculateHours);

    // Initial calculation
    calculateHours();
});
</script>
@endsection
