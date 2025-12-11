@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.teachers') }}
@endsection

@section('css')
<style>
    .page-header-custom {
        background: #06a465;
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
        background: #06a465;
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
    .current-info {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><i class="mdi mdi-account-edit me-2"></i> Modifier l'affectation</h3>
                <p>Modifier les informations de l'affectation enseignant-matière-classe</p>
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

                    <form action="{{ route('web.subjects_teachers.update', $subjectTeacher->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

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
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $subjectTeacher->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="mdi mdi-book-open-variant me-1"></i> Matière <span class="text-danger">*</span></label>
                                    <select name="subject_id" id="subject_id" class="form-select" required>
                                        <option value="">-- Sélectionner une matière --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $subjectTeacher->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                            <option value="{{ $classe->id }}" {{ old('class_id', $subjectTeacher->class_id) == $classe->id ? 'selected' : '' }}>
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
                                            <option value="{{ $trimester->id }}" {{ old('trimester_id', $subjectTeacher->trimester_id) == $trimester->id ? 'selected' : '' }}>
                                                {{ $trimester->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Heures hebdomadaires -->
                        <div class="section-title mt-4">
                            <i class="mdi mdi-clock-outline"></i> Volume horaire hebdomadaire
                        </div>

                        <div class="hours-input-group">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="mb-2"><i class="mdi mdi-clock-time-four me-1"></i> Heures par semaine <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-timer"></i>
                                        </span>
                                        <input type="number"
                                               class="form-control"
                                               id="heures_semaine"
                                               name="heures_semaine"
                                               min="0"
                                               max="40"
                                               step="0.5"
                                               value="{{ old('heures_semaine', $subjectTeacher->heures_semaine ?? 0) }}"
                                               placeholder="Ex: 18"
                                               required>
                                        <span class="input-group-text" style="background: #fff; border: 2px solid #e0e0e0; border-left: none; border-radius: 0 10px 10px 0;">
                                            h/semaine
                                        </span>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="mdi mdi-information-outline me-1"></i>
                                        Nombre d'heures que cet enseignant doit effectuer par semaine
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <div class="hours-info">
                                        <h6 class="mb-3"><i class="mdi mdi-calculator me-2"></i> Calcul automatique</h6>
                                        <div class="hours-info-item">
                                            <span class="label">Heures/mois (×4)</span>
                                            <span class="value" id="heures_mois">0 h</span>
                                        </div>
                                        <div class="hours-info-item">
                                            <span class="label">Heures/trimestre (×12)</span>
                                            <span class="value" id="heures_trimestre">0 h</span>
                                        </div>
                                        <div class="hours-info-item">
                                            <span class="label">Heures réelles</span>
                                            <span class="value text-primary">{{ $subjectTeacher->heures_reelles ?? 0 }} h</span>
                                        </div>
                                        <div class="hours-info-item">
                                            <span class="label">Taux de réalisation</span>
                                            <span class="value {{ $subjectTeacher->taux >= 100 ? 'text-success' : 'text-warning' }}">{{ $subjectTeacher->taux ?? 0 }}%</span>
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
                                    <i class="mdi mdi-content-save me-2"></i> Mettre à jour
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
                        <i class="mdi mdi-information me-2"></i> Informations actuelles
                    </h5>

                    <div class="current-info">
                        <div class="mb-2">
                            <strong><i class="mdi mdi-account me-1"></i> Professeur:</strong>
                            <span>{{ $subjectTeacher->teacher->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="mdi mdi-book me-1"></i> Matière:</strong>
                            <span>{{ $subjectTeacher->subject->name ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="mdi mdi-school me-1"></i> Classe:</strong>
                            <span>{{ $subjectTeacher->classe->nom ?? '-' }}</span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="mdi mdi-calendar me-1"></i> Trimestre:</strong>
                            <span>{{ $subjectTeacher->trimester->name ?? '-' }}</span>
                        </div>
                        <div>
                            <strong><i class="mdi mdi-clock me-1"></i> Heures/semaine:</strong>
                            <span>{{ $subjectTeacher->heures_semaine ?? 0 }} h</span>
                        </div>
                    </div>

                    <div class="alert mt-3" style="background: #fff3e0; border: 1px solid #ff9800; color: #e65100;">
                        <i class="mdi mdi-alert me-2"></i>
                        <strong>Attention:</strong>
                        <p class="mb-0 mt-2 small">
                            La modification de cette affectation peut affecter l'emploi du temps associé.
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
    // Calculate monthly and trimester hours
    function calculateHours() {
        var heuresSemaine = parseFloat($('#heures_semaine').val()) || 0;
        var heuresMois = heuresSemaine * 4;
        var heuresTrimestre = heuresSemaine * 12;

        $('#heures_mois').text(heuresMois + ' h');
        $('#heures_trimestre').text(heuresTrimestre + ' h');
    }

    // Update on input change
    $('#heures_semaine').on('input', calculateHours);

    // Initial calculation
    calculateHours();
});
</script>
@endsection
