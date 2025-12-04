@extends('layouts.masters.master')

@section('title')
    {{ __('Créer un emploi du temps') }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .page-header-custom {
        background: #1a1a1a;
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
    .selection-card {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        border: 2px solid #e0e0e0;
    }
    .selection-card .form-label {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    .selection-card .form-select {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .selection-card .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .info-alert {
        background: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        color: #1a1a1a;
    }
    .info-alert i {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    .emploi-row {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        position: relative;
    }
    .emploi-row:hover {
        border-color: #1a1a1a;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    .emploi-row.first-row {
        background: #fafafa;
        border-color: #1a1a1a;
    }
    .row-number {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #1a1a1a;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .form-group label {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }
    .form-group label .text-danger {
        color: #666 !important;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .select2-container--bootstrap-5 .select2-selection {
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        min-height: 45px !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #1a1a1a !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1) !important;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background: #1a1a1a !important;
        border: none !important;
        color: #fff !important;
        padding: 4px 10px !important;
        border-radius: 20px !important;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
        margin-right: 5px;
    }
    .btn-remove-row {
        background: #666;
        border: none;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-remove-row:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        background: #1a1a1a;
    }
    .btn-add-row {
        background: #fff;
        border: 2px solid #1a1a1a;
        color: #1a1a1a;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-add-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        background: #1a1a1a;
        color: #fff;
    }
    .btn-submit {
        background: #1a1a1a;
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
    .form-feedback {
        margin-top: 5px;
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .form-feedback.text-success {
        background: rgba(0, 0, 0, 0.05);
        color: #333 !important;
    }
    .form-feedback.text-warning {
        background: rgba(0, 0, 0, 0.05);
        color: #666 !important;
    }
    .form-feedback.text-danger {
        background: rgba(0, 0, 0, 0.05);
        color: #333 !important;
    }
    .is-invalid {
        border-color: #666 !important;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        z-index: 10;
    }
    .spinner-custom {
        width: 40px;
        height: 40px;
        border: 4px solid #e0e0e0;
        border-top: 4px solid #1a1a1a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .actions-row {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        margin-top: 25px;
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
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><i class="mdi mdi-calendar-plus me-2"></i> Nouvelle Séance</h3>
                <p>Créer un nouvel emploi du temps pour une classe</p>
            </div>
            <a href="{{ route('web.emplois.index') }}" class="btn btn-light">
                <i class="mdi mdi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="info-alert d-flex align-items-center mb-4">
        <i class="mdi mdi-information-outline"></i>
        <div>
            <strong>Information:</strong> Sélectionnez d'abord une classe et un trimestre. Les enseignants assignés à cette classe pour ce trimestre seront automatiquement chargés avec leurs matières.
        </div>
    </div>

    <form action="{{ route('web.emplois.store') }}" method="POST" id="emploiForm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="mdi mdi-alert-circle"></i> Erreurs détectées:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Selection Card -->
        <div class="card main-card">
            <div class="card-body">
                <div class="section-title">
                    <i class="mdi mdi-tune"></i> Configuration principale
                </div>
                <div class="selection-card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-account-group me-1"></i> Classe <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_classe_id" class="form-select" required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="mdi mdi-calendar-range me-1"></i> Trimestre <span class="text-danger">*</span></label>
                                <select name="trimester_id" id="trimester_create_id" class="form-select" required disabled>
                                    <option value="">-- Sélectionner d'abord une classe --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-title">
                    <i class="mdi mdi-calendar-clock"></i> Séances de cours
                </div>

                <div id="emploi-rows">
                    <!-- First Row Template -->
                    <div class="emploi-row first-row" data-index="0">
                        <span class="row-number">1</span>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="mdi mdi-account-tie me-1"></i> Enseignant <span class="text-danger">*</span></label>
                                    <select name="teacher_id[]" class="form-select teacher-select" required disabled>
                                        <option value="">-- Sélectionner classe et trimestre --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="mdi mdi-book-open-variant me-1"></i> Matière <span class="text-danger">*</span></label>
                                    <select name="subject_id[]" class="form-select subject-select" required disabled>
                                        <option value="">-- Sélectionner un enseignant --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="mdi mdi-calendar-today me-1"></i> Jour <span class="text-danger">*</span></label>
                                    <select name="jour_id[]" class="form-select" required>
                                        <option value="">-- Jour --</option>
                                        @foreach($jours as $jour)
                                            <option value="{{ $jour->id }}">{{ $jour->libelle_fr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="mdi mdi-clock-outline me-1"></i> Horaires <span class="text-danger">*</span></label>
                                    <select name="horaire_id[0][]" class="form-select horaire-select-multiple" multiple="multiple" required>
                                        @foreach($horaires as $horaire)
                                            <option value="{{ $horaire->id }}">{{ $horaire->libelle_fr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><i class="mdi mdi-door me-1"></i> Salle</label>
                                    <select name="salle_de_classe_id[]" class="form-select">
                                        <option value="">-- Optionnel --</option>
                                        @foreach($salles as $salle)
                                            <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-row">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <button type="button" id="add-row" class="btn btn-add-row">
                            <i class="mdi mdi-plus-circle me-2"></i> Ajouter une autre séance
                        </button>
                        <div class="d-flex gap-3">
                            <a href="{{ route('web.emplois.index') }}" class="btn btn-back">
                                <i class="mdi mdi-close me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="mdi mdi-content-save me-2"></i> Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Row Template for cloning -->
<template id="row-template">
    <div class="emploi-row" data-index="INDEX">
        <span class="row-number">NUM</span>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label><i class="mdi mdi-account-tie me-1"></i> Enseignant <span class="text-danger">*</span></label>
                    <select name="teacher_id[]" class="form-select teacher-select" required>
                        <option value="">-- Choisir --</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label><i class="mdi mdi-book-open-variant me-1"></i> Matière <span class="text-danger">*</span></label>
                    <select name="subject_id[]" class="form-select subject-select" required disabled>
                        <option value="">-- Sélectionner un enseignant --</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label><i class="mdi mdi-calendar-today me-1"></i> Jour <span class="text-danger">*</span></label>
                    <select name="jour_id[]" class="form-select" required>
                        <option value="">-- Jour --</option>
                        @foreach($jours as $jour)
                            <option value="{{ $jour->id }}">{{ $jour->libelle_fr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label><i class="mdi mdi-clock-outline me-1"></i> Horaires <span class="text-danger">*</span></label>
                    <select name="horaire_id[INDEX][]" class="form-select horaire-select-multiple" multiple="multiple" required>
                        @foreach($horaires as $horaire)
                            <option value="{{ $horaire->id }}">{{ $horaire->libelle_fr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label><i class="mdi mdi-door me-1"></i> Salle</label>
                    <select name="salle_de_classe_id[]" class="form-select">
                        <option value="">--</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end pb-2">
                <button type="button" class="btn-remove-row" title="Supprimer cette séance">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    let teachersData = [];

    // Initialize Select2 for time slots
    function initSelect2(container) {
        container.find('.horaire-select-multiple').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionner...',
            allowClear: true,
            closeOnSelect: false,
            width: '100%'
        });
    }

    // Initialize first row
    initSelect2($('#emploi-rows'));

    // Load trimesters when class changes
    $('#class_classe_id').on('change', function() {
        let classId = $(this).val();
        let trimesterSelect = $('#trimester_create_id');

        // Reset trimesters
        trimesterSelect.empty().append('<option value="">-- Chargement... --</option>');
        trimesterSelect.prop('disabled', true);

        // Reset all rows
        resetAllRows();

        if (classId) {
            $.ajax({
                url: baseUrl + '/admin/emplois/get-trimesters',
                type: 'POST',
                data: { class_id: classId, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    trimesterSelect.empty().append('<option value="">-- Sélectionner un trimestre --</option>');
                    if (response.trimesters && response.trimesters.length > 0) {
                        $.each(response.trimesters, function(key, trimestre) {
                            trimesterSelect.append(`<option value="${trimestre.id}">${trimestre.name}</option>`);
                        });
                        trimesterSelect.prop('disabled', false);
                    } else {
                        trimesterSelect.append('<option value="">-- Aucun trimestre disponible --</option>');
                    }
                },
                error: function() {
                    trimesterSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                }
            });
        } else {
            trimesterSelect.empty().append('<option value="">-- Sélectionner d\'abord une classe --</option>');
        }
    });

    // Load teachers when trimester changes
    $('#trimester_create_id').on('change', function() {
        let classId = $('#class_classe_id').val();
        let trimesterId = $(this).val();

        if (classId && trimesterId) {
            loadTeachersForAllRows(classId, trimesterId);
        } else {
            resetAllRows();
        }
    });

    // Load teachers for all rows
    function loadTeachersForAllRows(classId, trimesterId) {
        $('.emploi-row').each(function() {
            let row = $(this);
            let teacherSelect = row.find('.teacher-select');
            let subjectSelect = row.find('.subject-select');

            teacherSelect.empty().append('<option value="">Chargement...</option>');
            teacherSelect.prop('disabled', true);
            subjectSelect.empty().append('<option value="">-- Sélectionner un enseignant --</option>');
            subjectSelect.prop('disabled', true);
        });

        $.ajax({
            url: baseUrl + '/admin/emplois/get-teachers',
            type: 'GET',
            data: { class_id: classId, trimester_id: trimesterId },
            success: function(response) {
                teachersData = response.data || [];

                $('.emploi-row').each(function() {
                    let row = $(this);
                    let teacherSelect = row.find('.teacher-select');

                    teacherSelect.empty().append('<option value="">-- Sélectionner un enseignant --</option>');

                    if (teachersData.length > 0) {
                        $.each(teachersData, function(key, teacher) {
                            let displayName = teacher.full_name || teacher.name;
                            let subjectsInfo = '';
                            if (teacher.subjects && teacher.subjects.length > 0) {
                                let subjectNames = teacher.subjects.map(s => s.name).join(', ');
                                subjectsInfo = ` (${subjectNames})`;
                            }
                            teacherSelect.append(`<option value="${teacher.id}" data-subjects='${JSON.stringify(teacher.subjects || [])}'>${displayName}${subjectsInfo}</option>`);
                        });
                        teacherSelect.prop('disabled', false);

                        // Show feedback
                        showFeedback(teacherSelect, `${teachersData.length} enseignant(s) disponible(s)`, 'success');
                    } else {
                        teacherSelect.append('<option value="">-- Aucun enseignant disponible --</option>');
                        showFeedback(teacherSelect, 'Aucun enseignant assigné pour ce trimestre', 'warning');
                    }
                });
            },
            error: function() {
                $('.emploi-row').each(function() {
                    let teacherSelect = $(this).find('.teacher-select');
                    teacherSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                    teacherSelect.prop('disabled', false);
                    showFeedback(teacherSelect, 'Erreur lors du chargement', 'danger');
                });
            }
        });
    }

    // Load subjects when teacher changes
    $(document).on('change', '.teacher-select', function() {
        let row = $(this).closest('.emploi-row');
        let teacherId = $(this).val();
        let subjectSelect = row.find('.subject-select');
        let teacherOption = $(this).find('option:selected');
        let teacherSubjects = teacherOption.data('subjects');

        subjectSelect.empty().append('<option value="">-- Sélectionner une matière --</option>');

        if (teacherId && teacherSubjects && teacherSubjects.length > 0) {
            $.each(teacherSubjects, function(key, subject) {
                let displayName = subject.name;
                if (subject.coefficient) {
                    displayName += ` (Coef: ${subject.coefficient})`;
                }
                subjectSelect.append(`<option value="${subject.id}">${displayName}</option>`);
            });
            subjectSelect.prop('disabled', false);
            showFeedback(subjectSelect, `${teacherSubjects.length} matière(s) disponible(s)`, 'success');
        } else if (teacherId) {
            // Fallback: load from server
            let classId = $('#class_classe_id').val();
            let trimesterId = $('#trimester_create_id').val();

            $.ajax({
                url: baseUrl + '/admin/emplois/get-subjects',
                type: 'GET',
                data: { teacher_id: teacherId, class_id: classId, trimester_id: trimesterId },
                success: function(response) {
                    subjectSelect.empty().append('<option value="">-- Sélectionner une matière --</option>');
                    if (response.subjects && response.subjects.length > 0) {
                        $.each(response.subjects, function(key, subject) {
                            subjectSelect.append(`<option value="${subject.id}">${subject.name}</option>`);
                        });
                        subjectSelect.prop('disabled', false);
                        showFeedback(subjectSelect, `${response.subjects.length} matière(s) disponible(s)`, 'success');
                    } else {
                        showFeedback(subjectSelect, 'Aucune matière pour cet enseignant', 'warning');
                    }
                }
            });
        } else {
            subjectSelect.prop('disabled', true);
            removeFeedback(subjectSelect);
        }
    });

    // Reset all rows
    function resetAllRows() {
        $('.emploi-row').each(function() {
            let teacherSelect = $(this).find('.teacher-select');
            let subjectSelect = $(this).find('.subject-select');

            teacherSelect.empty().append('<option value="">-- Sélectionner classe et trimestre --</option>');
            teacherSelect.prop('disabled', true);
            subjectSelect.empty().append('<option value="">-- Sélectionner un enseignant --</option>');
            subjectSelect.prop('disabled', true);

            removeFeedback(teacherSelect);
            removeFeedback(subjectSelect);
        });
    }

    // Show feedback message
    function showFeedback(element, message, type) {
        let feedbackDiv = element.parent().find('.form-feedback');
        if (feedbackDiv.length === 0) {
            feedbackDiv = $('<div class="form-feedback"></div>');
            element.after(feedbackDiv);
        }

        let icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'alert' : 'alert-circle');
        feedbackDiv.html(`<i class="mdi mdi-${icon} me-1"></i>${message}`)
            .removeClass('text-success text-warning text-danger')
            .addClass('text-' + type);
    }

    // Remove feedback message
    function removeFeedback(element) {
        element.parent().find('.form-feedback').remove();
    }

    // Add new row
    $('#add-row').on('click', function() {
        let classId = $('#class_classe_id').val();
        let trimesterId = $('#trimester_create_id').val();

        if (!classId || !trimesterId) {
            alert('Veuillez d\'abord sélectionner une classe et un trimestre.');
            return;
        }

        // Validate last row
        let lastRow = $('.emploi-row').last();
        let valid = true;

        lastRow.find('select[required]').each(function() {
            if (!$(this).val() || (Array.isArray($(this).val()) && $(this).val().length === 0)) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            alert('Veuillez remplir tous les champs obligatoires de la dernière séance avant d\'en ajouter une nouvelle.');
            return;
        }

        let rowCount = $('.emploi-row').length;
        let template = $('#row-template').html();
        template = template.replace(/INDEX/g, rowCount).replace(/NUM/g, rowCount + 1);

        let newRow = $(template);
        $('#emploi-rows').append(newRow);

        // Initialize Select2
        initSelect2(newRow);

        // Populate teachers
        let teacherSelect = newRow.find('.teacher-select');
        teacherSelect.empty().append('<option value="">-- Sélectionner un enseignant --</option>');

        if (teachersData.length > 0) {
            $.each(teachersData, function(key, teacher) {
                let displayName = teacher.full_name || teacher.name;
                let subjectsInfo = '';
                if (teacher.subjects && teacher.subjects.length > 0) {
                    let subjectNames = teacher.subjects.map(s => s.name).join(', ');
                    subjectsInfo = ` (${subjectNames})`;
                }
                teacherSelect.append(`<option value="${teacher.id}" data-subjects='${JSON.stringify(teacher.subjects || [])}'>${displayName}${subjectsInfo}</option>`);
            });
        }

        // Update row numbers
        updateRowNumbers();
    });

    // Remove row
    $(document).on('click', '.btn-remove-row', function() {
        if ($('.emploi-row').length > 1) {
            $(this).closest('.emploi-row').remove();
            updateRowNumbers();
        }
    });

    // Update row numbers
    function updateRowNumbers() {
        $('.emploi-row').each(function(index) {
            $(this).find('.row-number').text(index + 1);
            $(this).attr('data-index', index);
            $(this).find('.horaire-select-multiple').attr('name', `horaire_id[${index}][]`);

            // First row styling
            if (index === 0) {
                $(this).addClass('first-row');
                $(this).find('.btn-remove-row').parent().remove();
            } else {
                $(this).removeClass('first-row');
            }
        });
    }

    // Form validation
    $('#emploiForm').on('submit', function(e) {
        let valid = true;
        let errors = [];

        // Check main fields
        if (!$('#class_classe_id').val()) {
            $('#class_classe_id').addClass('is-invalid');
            errors.push('Veuillez sélectionner une classe');
            valid = false;
        } else {
            $('#class_classe_id').removeClass('is-invalid');
        }

        if (!$('#trimester_create_id').val()) {
            $('#trimester_create_id').addClass('is-invalid');
            errors.push('Veuillez sélectionner un trimestre');
            valid = false;
        } else {
            $('#trimester_create_id').removeClass('is-invalid');
        }

        // Check each row
        $('.emploi-row').each(function(index) {
            let rowNum = index + 1;
            $(this).find('select[required]').each(function() {
                let val = $(this).val();
                if (!val || (Array.isArray(val) && val.length === 0)) {
                    $(this).addClass('is-invalid');
                    let fieldName = $(this).closest('.form-group').find('label').text().replace('*', '').trim();
                    errors.push(`Séance ${rowNum}: ${fieldName} est requis`);
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });

        if (!valid) {
            e.preventDefault();
            alert('Erreurs de validation:\n\n' + errors.join('\n'));
        }
    });
});
</script>
@endsection
