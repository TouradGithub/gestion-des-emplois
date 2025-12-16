@extends('layouts.masters.master')

@section('title')
    {{ __('Année scolaire') }} - {{ $anneescolaire->annee }}
@endsection

@section('css')
<style>
    .year-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: #fff;
        margin-bottom: 25px;
    }
    .year-header h2 {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .year-header .badge-active {
        background: #28a745;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    .year-header .badge-inactive {
        background: #dc3545;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    .stats-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-card .icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .stats-card .number {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
    .stats-card .label {
        color: #666;
        font-size: 0.9rem;
    }
    .classes-section {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .classe-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }
    .classe-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .classe-card .classe-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }
    .classe-card .classe-info {
        color: #666;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    .classe-card .students-count {
        background: #667eea;
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .btn-clone {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: #fff;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-clone:hover {
        transform: scale(1.05);
        color: #fff;
        box-shadow: 0 5px 20px rgba(17, 153, 142, 0.4);
    }
    .clone-modal .modal-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        border-radius: 15px 15px 0 0;
    }
    .clone-modal .modal-content {
        border-radius: 15px;
        border: none;
    }
    .clone-modal .classe-checkbox {
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s;
        overflow: visible;
    }
    .clone-modal .classe-checkbox:hover {
        background: #e9ecef;
    }
    .clone-modal .classe-checkbox.selected {
        background: #d4edda;
        border-left: 3px solid #28a745;
    }
    .clone-modal .form-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-left: 0;
        margin: 0;
    }
    .clone-modal .form-check-input {
        width: 18px;
        height: 18px;
        min-width: 18px;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
    }
    .clone-modal .form-check-label {
        flex: 1;
        cursor: pointer;
        margin: 0;
        padding-left: 0;
    }
    .clone-modal .classe-checkbox strong {
        color: #333;
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i {
        color: #667eea;
    }
    /* Select2 styling for modals */
    .modal .select2-container {
        width: 100% !important;
    }
    .modal .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    .modal .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    .modal .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-dropdown {
        z-index: 9999 !important;
    }

    /* Assign Students Modal Styles */
    .assign-students-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 15px 15px 0 0;
    }
    .assign-students-modal .modal-content {
        border-radius: 15px;
        border: none;
    }

    /* Stepper Styles */
    .stepper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 60px;
        right: 60px;
        height: 3px;
        background: #e9ecef;
        z-index: 0;
    }
    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }
    .stepper-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #6c757d;
        transition: all 0.3s;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .stepper-item.active .stepper-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        transform: scale(1.1);
    }
    .stepper-item.completed .stepper-icon {
        background: #28a745;
        color: #fff;
    }
    .stepper-label {
        margin-top: 10px;
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    .stepper-item.active .stepper-label {
        color: #667eea;
        font-weight: 600;
    }
    .stepper-item.completed .stepper-label {
        color: #28a745;
    }

    /* Step Content */
    .step-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    .step-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Niveau/Class Selection Cards */
    .selection-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    .selection-card:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    .selection-card.selected {
        background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);
        border-color: #667eea;
    }
    .selection-card .card-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.3rem;
    }
    .selection-card .card-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    .selection-card .card-subtitle {
        font-size: 0.85rem;
        color: #666;
    }

    /* Students List */
    .students-search-box {
        position: relative;
        margin-bottom: 15px;
    }
    .students-search-box input {
        padding-left: 40px;
        border-radius: 25px;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
    }
    .students-search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .students-search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .students-list {
        max-height: 350px;
        overflow-y: auto;
    }
    .student-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .student-item:hover {
        background: #e9ecef;
    }
    .student-item.selected {
        background: #d4edda;
        border-left: 3px solid #28a745;
    }
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .student-info {
        flex: 1;
    }
    .student-name {
        font-weight: 600;
        color: #333;
    }
    .student-details {
        font-size: 0.8rem;
        color: #666;
    }
    .student-checkbox {
        width: 22px;
        height: 22px;
        cursor: pointer;
    }

    /* Summary */
    .summary-box {
        background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #667eea30;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #ddd;
    }
    .summary-item:last-child {
        border-bottom: none;
    }
    .summary-label {
        color: #666;
    }
    .summary-value {
        font-weight: 600;
        color: #333;
    }
    .selected-students-preview {
        max-height: 200px;
        overflow-y: auto;
    }
    .selected-student-badge {
        display: inline-flex;
        align-items: center;
        background: #667eea;
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        margin: 3px;
        font-size: 0.85rem;
    }
    .selected-student-badge .remove-student {
        margin-left: 8px;
        cursor: pointer;
        opacity: 0.8;
    }
    .selected-student-badge .remove-student:hover {
        opacity: 1;
    }

    /* Navigation Buttons */
    .step-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }
    .btn-step {
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-step-prev {
        background: #e9ecef;
        color: #333;
        border: none;
    }
    .btn-step-prev:hover {
        background: #ddd;
        color: #333;
    }
    .btn-step-next {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
    }
    .btn-step-next:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .btn-step-next:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Add Students Button */
    .btn-add-students {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-add-students:hover {
        transform: scale(1.05);
        color: #fff;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    /* Empty state for students */
    .students-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    .students-empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Year Header -->
    <div class="year-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="mdi mdi-calendar-star"></i> {{ $anneescolaire->annee }}</h2>
                <p class="mb-0">
                    <i class="mdi mdi-calendar-range"></i>
                    {{ \Carbon\Carbon::parse($anneescolaire->date_debut)->format('d/m/Y') }}
                    -
                    {{ \Carbon\Carbon::parse($anneescolaire->date_fin)->format('d/m/Y') }}
                </p>
            </div>
            <div>
                @if($anneescolaire->is_active)
                    <span class="badge-active"><i class="mdi mdi-check-circle"></i> Active</span>
                @else
                    <span class="badge-inactive"><i class="mdi mdi-close-circle"></i> Inactive</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="icon text-primary"><i class="mdi mdi-school"></i></div>
                <div class="number">{{ $anneescolaire->classes->count() }}</div>
                <div class="label">Classes</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="icon text-success"><i class="mdi mdi-account-group"></i></div>
                <div class="number">{{ $anneescolaire->classes->sum(function($c) { return $c->students->count(); }) }}</div>
                <div class="label">Etudiants</div>
            </div>
        </div>
    </div>

    <!-- Classes Section -->
    <div class="classes-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title mb-0">
                <i class="mdi mdi-view-grid"></i> Liste des Classes
            </h4>
            <div>
                <button type="button" class="btn btn-primary me-2" id="btnNewClasse">
                    <i class="mdi mdi-plus"></i> Nouvelle classe
                </button>
                @if($autresAnnees->count() > 0)
                    <button type="button" class="btn btn-clone" id="btnOpenCloneModal">
                        <i class="mdi mdi-content-copy"></i> Cloner des classes
                    </button>
                @endif
            </div>
        </div>

        @if($anneescolaire->classes->count() > 0)
            <div class="row">
                @foreach($anneescolaire->classes->sortBy('nom') as $classe)
                    <div class="col-md-6 col-lg-4">
                        <div class="classe-card classe-clickable"
                             data-id="{{ $classe->id }}"
                             data-name="{{ $classe->nom }}"
                             data-niveau="{{ $classe->niveau->nom ?? '' }}"
                             style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="classe-name">{{ $classe->nom }}</div>
                                    <div class="classe-info">
                                        @if($classe->niveau)
                                            <span><i class="mdi mdi-layers"></i> {{ $classe->niveau->nom }}</span>
                                        @endif
                                        @if($classe->specialite)
                                            <span class="ms-2"><i class="mdi mdi-book"></i> {{ $classe->specialite->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="students-count">
                                    <i class="mdi mdi-account"></i> {{ $classe->students->count() }}
                                </span>
                            </div>
                            <div class="mt-2 text-center">
                                <small class="text-muted"><i class="mdi mdi-plus-circle"></i> Cliquez pour ajouter des etudiants</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="mdi mdi-school-outline"></i>
                <h5 class="text-muted">Aucune classe pour cette annee</h5>
                <p class="text-muted">Commencez par ajouter une nouvelle classe ou cloner depuis une autre annee.</p>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('web.anneescolaires.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Retour a la liste
        </a>
    </div>
</div>

<!-- Create Class Modal -->
<div class="modal fade" id="createClasseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-plus-circle"></i> Nouvelle Classe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createClasseForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Niveau Pedagogique <span class="text-danger">*</span></label>
                        <select class="form-select" id="niveau_pedagogique_id" name="niveau_pedagogique_id" required>
                            <option value="">-- Selectionner un niveau --</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Specialite <span class="text-danger">*</span></label>
                        <select class="form-select" id="specialite_id" name="specialite_id" required>
                            <option value="">-- Selectionner une specialite --</option>
                            @foreach($specialites as $specialite)
                                <option value="{{ $specialite->id }}">{{ $specialite->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="annee_id" value="{{ $anneescolaire->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnSaveClasse">
                    <i class="mdi mdi-content-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Assign Students Modal -->
<div class="modal fade assign-students-modal" id="assignStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-account-plus"></i> Ajouter des etudiants a: <span id="targetClassName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Target Class Info -->
                <div class="alert alert-info mb-4">
                    <i class="mdi mdi-information"></i>
                    Vous allez ajouter des etudiants a la classe <strong id="targetClassInfo"></strong>
                </div>

                <!-- Stepper -->
                <div class="stepper">
                    <div class="stepper-item active" data-step="1">
                        <div class="stepper-icon"><i class="mdi mdi-calendar"></i></div>
                        <div class="stepper-label">Annee</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="stepper-icon"><i class="mdi mdi-school"></i></div>
                        <div class="stepper-label">Classe Source</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="stepper-icon"><i class="mdi mdi-account-group"></i></div>
                        <div class="stepper-label">Etudiants</div>
                    </div>
                    <div class="stepper-item" data-step="4">
                        <div class="stepper-icon"><i class="mdi mdi-check-all"></i></div>
                        <div class="stepper-label">Confirmation</div>
                    </div>
                </div>

                <!-- Step 1: Select Year -->
                <div class="step-content active" id="step1">
                    <h6 class="mb-3"><i class="mdi mdi-calendar text-primary"></i> Selectionnez l'annee scolaire source</h6>
                    <div id="yearsList">
                        @foreach($autresAnnees as $annee)
                        <div class="selection-card year-card" data-id="{{ $annee->id }}" data-name="{{ $annee->annee }}">
                            <div class="d-flex align-items-center">
                                <div class="card-icon me-3">
                                    <i class="mdi mdi-calendar"></i>
                                </div>
                                <div>
                                    <div class="card-title">{{ $annee->annee }}</div>
                                    <div class="card-subtitle">
                                        {{ \Carbon\Carbon::parse($annee->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($annee->date_fin)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if($autresAnnees->count() == 0)
                        <div class="students-empty-state">
                            <i class="mdi mdi-calendar-remove"></i>
                            <p>Aucune autre annee scolaire disponible</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Step 2: Select Source Class -->
                <div class="step-content" id="step2">
                    <h6 class="mb-3"><i class="mdi mdi-school text-primary"></i> Selectionnez la classe source</h6>
                    <div id="classesListAssign">
                        <!-- Classes will be loaded here -->
                    </div>
                    <div id="loadingClasses2" style="display: none; text-align: center; padding: 30px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2 text-muted">Chargement des classes...</p>
                    </div>
                    <div id="noClasses2" style="display: none;">
                        <div class="students-empty-state">
                            <i class="mdi mdi-school-outline"></i>
                            <p>Aucune classe disponible pour cette annee</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Select Students -->
                <div class="step-content" id="step3">
                    <h6 class="mb-3"><i class="mdi mdi-account-group text-primary"></i> Selectionnez les etudiants a transferer</h6>

                    <div class="students-search-box">
                        <i class="mdi mdi-magnify"></i>
                        <input type="text" class="form-control" id="searchStudents" placeholder="Rechercher par nom ou NNI...">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted"><span id="selectedCount">0</span> etudiant(s) selectionne(s)</small>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllStudents">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Tout
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllStudents">
                                <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Aucun
                            </button>
                        </div>
                    </div>

                    <div class="students-list" id="studentsList">
                        <!-- Students will be loaded here -->
                    </div>
                    <div id="loadingStudents" style="display: none; text-align: center; padding: 30px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2 text-muted">Chargement des etudiants...</p>
                    </div>
                    <div id="noStudents" style="display: none;">
                        <div class="students-empty-state">
                            <i class="mdi mdi-account-off"></i>
                            <p>Aucun etudiant dans cette classe</p>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Confirmation -->
                <div class="step-content" id="step4">
                    <h6 class="mb-3"><i class="mdi mdi-check-all text-primary"></i> Confirmation du transfert</h6>

                    <div class="summary-box">
                        <div class="summary-item">
                            <span class="summary-label"><i class="mdi mdi-arrow-right-bold me-2"></i>Classe cible</span>
                            <span class="summary-value" id="summaryTargetClasse">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label"><i class="mdi mdi-calendar me-2"></i>Annee source</span>
                            <span class="summary-value" id="summaryYear">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label"><i class="mdi mdi-school me-2"></i>Classe source</span>
                            <span class="summary-value" id="summarySourceClasse">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label"><i class="mdi mdi-account-group me-2"></i>Nombre d'etudiants</span>
                            <span class="summary-value" id="summaryCount">0</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Etudiants a transferer:</label>
                        <div class="selected-students-preview" id="selectedStudentsPreview">
                            <!-- Selected students badges will appear here -->
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="step-navigation">
                    <button type="button" class="btn btn-step btn-step-prev" id="btnPrevStep" style="visibility: hidden;">
                        <i class="mdi mdi-arrow-left"></i> Precedent
                    </button>
                    <button type="button" class="btn btn-step btn-step-next" id="btnNextStep" disabled>
                        Suivant <i class="mdi mdi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clone Modal -->
<div class="modal fade clone-modal" id="cloneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-content-copy"></i> Cloner des classes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Select Year -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Selectionner l'annee source:</label>
                    <select class="form-select" id="sourceYear">
                        <option value="">-- Choisir une annee --</option>
                        @foreach($autresAnnees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->annee }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Classes List -->
                <div id="classesContainer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold mb-0">Selectionner les classes a cloner:</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Tout selectionner
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Tout deselectionner
                            </button>
                        </div>
                    </div>
                    <div id="classesList" class="classes-list" style="max-height: 300px; overflow-y: auto;">
                        <!-- Classes will be loaded here -->
                    </div>

                    <!-- Option avec emploi du temps -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="avecEmploi" name="avec_emploi">
                            <label class="form-check-label fw-bold" for="avecEmploi">
                                <i class="mdi mdi-calendar-clock text-primary"></i> Cloner avec l'emploi du temps
                            </label>
                            <small class="d-block text-muted mt-1">Si active, les emplois du temps seront egalement copies avec les classes</small>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loadingClasses" style="display: none; text-align: center; padding: 30px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des classes...</p>
                </div>

                <!-- Empty State -->
                <div id="noClasses" style="display: none; text-align: center; padding: 30px;">
                    <i class="mdi mdi-alert-circle-outline" style="font-size: 3rem; color: #ffc107;"></i>
                    <p class="mt-2 text-muted">Aucune classe disponible dans cette annee.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-clone" id="btnClone" disabled>
                    <i class="mdi mdi-content-copy"></i> Cloner les classes selectionnees
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    const SITEURL = "{{ url('/') }}";
    let selectedClasses = [];

    // Initialize Select2 for modals
    $('#niveau_pedagogique_id').select2({
        dropdownParent: $('#createClasseModal'),
        placeholder: '-- Selectionner un niveau --',
        allowClear: true
    });

    $('#specialite_id').select2({
        dropdownParent: $('#createClasseModal'),
        placeholder: '-- Selectionner une specialite --',
        allowClear: true
    });

    $('#sourceYear').select2({
        dropdownParent: $('#cloneModal'),
        placeholder: '-- Choisir une annee --',
        allowClear: true
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Open Create Class Modal
    $('#btnNewClasse').on('click', function() {
        $('#niveau_pedagogique_id').val('').trigger('change');
        $('#specialite_id').val('').trigger('change');
        $('#createClasseModal').modal('show');
    });

    // Close modals
    $('.btn-close, .btn-secondary[data-bs-dismiss="modal"]').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });

    // Save new class
    $('#btnSaveClasse').on('click', function() {
        let niveauId = $('#niveau_pedagogique_id').val();
        let specialiteId = $('#specialite_id').val();

        if (!niveauId || !specialiteId) {
            Swal.fire('Attention!', 'Veuillez selectionner un niveau et une specialite', 'warning');
            return;
        }

        $(this).prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Enregistrement...');

        $.ajax({
            url: SITEURL + '/admin/anneescolaires/{{ $anneescolaire->id }}/store-classe',
            type: 'POST',
            data: {
                niveau_pedagogique_id: niveauId,
                specialite_id: specialiteId,
                annee_id: {{ $anneescolaire->id }}
            },
            success: function(response) {
                if (response.success) {
                    $('#createClasseModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succes!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                    $('#btnSaveClasse').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Enregistrer');
                }
            },
            error: function(xhr) {
                let message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Erreur!', message, 'error');
                $('#btnSaveClasse').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> Enregistrer');
            }
        });
    });

    // Open Clone Modal
    $('#btnOpenCloneModal').on('click', function() {
        $('#sourceYear').val('').trigger('change');
        $('#classesContainer').hide();
        $('#noClasses').hide();
        $('#classesList').html('');
        $('#btnClone').prop('disabled', true);
        $('#cloneModal').modal('show');
    });

    // Load classes when year is selected (using select2:select event)
    $('#sourceYear').on('select2:select select2:clear change', function() {
        let anneeId = $(this).val();

        console.log('Year selected:', anneeId); // Debug

        if (!anneeId) {
            $('#classesContainer').hide();
            $('#noClasses').hide();
            $('#btnClone').prop('disabled', true);
            return;
        }

        $('#loadingClasses').show();
        $('#classesContainer').hide();
        $('#noClasses').hide();

        $.ajax({
            url: SITEURL + '/admin/anneescolaires/get-classes',
            type: 'GET',
            data: { annee_id: anneeId },
            success: function(response) {
                console.log('Classes loaded:', response); // Debug
                $('#loadingClasses').hide();

                if (response.classes && response.classes.length > 0) {
                    renderClasses(response.classes);
                    $('#classesContainer').show();
                } else {
                    $('#noClasses').show();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error); // Debug
                $('#loadingClasses').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des classes', 'error');
            }
        });
    });

    function renderClasses(classes) {
        let html = '';
        selectedClasses = [];

        classes.forEach(function(classe) {
            let emploiCount = classe.emplois_count || 0;
            let emploiBadge = emploiCount > 0
                ? `<span class="badge bg-success ms-2"><i class="mdi mdi-calendar-check"></i> ${emploiCount} emploi(s)</span>`
                : `<span class="badge bg-secondary ms-2"><i class="mdi mdi-calendar-remove"></i> Aucun emploi</span>`;

            html += `
                <div class="classe-checkbox" data-id="${classe.id}">
                    <div class="form-check">
                        <input class="form-check-input classe-check" type="checkbox" value="${classe.id}" id="classe_${classe.id}">
                        <label class="form-check-label w-100" for="classe_${classe.id}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${classe.nom}</strong>
                                    <span class="text-muted ms-2">(${classe.specialite})</span>
                                    ${emploiBadge}
                                </div>
                                <small class="text-primary">${classe.niveau}</small>
                            </div>
                        </label>
                    </div>
                </div>
            `;
        });

        $('#classesList').html(html);
        $('#avecEmploi').prop('checked', false);
        updateCloneButton();
    }

    // Handle checkbox change
    $(document).on('change', '.classe-check', function() {
        let $parent = $(this).closest('.classe-checkbox');
        if ($(this).is(':checked')) {
            $parent.addClass('selected');
        } else {
            $parent.removeClass('selected');
        }
        updateCloneButton();
    });

    // Select all
    $('#selectAll').on('click', function() {
        $('.classe-check').prop('checked', true);
        $('.classe-checkbox').addClass('selected');
        updateCloneButton();
    });

    // Deselect all
    $('#deselectAll').on('click', function() {
        $('.classe-check').prop('checked', false);
        $('.classe-checkbox').removeClass('selected');
        updateCloneButton();
    });

    function updateCloneButton() {
        let checkedCount = $('.classe-check:checked').length;
        $('#btnClone').prop('disabled', checkedCount === 0);
        if (checkedCount > 0) {
            $('#btnClone').html(`<i class="mdi mdi-content-copy"></i> Cloner ${checkedCount} classe(s)`);
        } else {
            $('#btnClone').html('<i class="mdi mdi-content-copy"></i> Cloner les classes selectionnees');
        }
    }

    // Clone classes
    $('#btnClone').on('click', function() {
        let selectedIds = [];
        $('.classe-check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire('Attention!', 'Veuillez selectionner au moins une classe', 'warning');
            return;
        }

        let avecEmploi = $('#avecEmploi').is(':checked');

        $(this).prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Clonage en cours...');

        $.ajax({
            url: SITEURL + '/admin/anneescolaires/{{ $anneescolaire->id }}/clone-classes',
            type: 'POST',
            data: {
                classes: selectedIds,
                avec_emploi: avecEmploi ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succes!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                    updateCloneButton();
                }
            },
            error: function(xhr) {
                let message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Erreur!', message, 'error');
                updateCloneButton();
            }
        });
    });

    // Reset modal on close
    $('#cloneModal').on('hidden.bs.modal', function() {
        $('#sourceYear').val('');
        $('#classesContainer').hide();
        $('#noClasses').hide();
        $('#classesList').html('');
        $('#btnClone').prop('disabled', true);
    });

    // ============================================
    // ASSIGN STUDENTS MODAL FUNCTIONALITY
    // ============================================

    let currentStep = 1;
    let targetClasse = null;      // The class we clicked on (destination)
    let selectedYear = null;      // Source year
    let sourceClasse = null;      // Source class
    let selectedStudents = [];
    let allStudents = [];

    // Open Assign Students Modal when clicking on a class card
    $(document).on('click', '.classe-clickable', function() {
        targetClasse = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            niveau: $(this).data('niveau')
        };

        resetAssignModal();
        $('#targetClassName').text(targetClasse.name);
        $('#targetClassInfo').text(targetClasse.name);
        $('#assignStudentsModal').modal('show');
    });

    // Reset modal
    function resetAssignModal() {
        currentStep = 1;
        selectedYear = null;
        sourceClasse = null;
        selectedStudents = [];
        allStudents = [];

        // Reset stepper
        $('.stepper-item').removeClass('active completed');
        $('.stepper-item[data-step="1"]').addClass('active');

        // Reset steps
        $('.step-content').removeClass('active');
        $('#step1').addClass('active');

        // Reset selections
        $('.year-card').removeClass('selected');
        $('#classesListAssign').html('');
        $('#studentsList').html('');
        $('#selectedStudentsPreview').html('');
        $('#searchStudents').val('');

        // Reset buttons
        $('#btnPrevStep').css('visibility', 'hidden');
        $('#btnNextStep').prop('disabled', true).html('Suivant <i class="mdi mdi-arrow-right"></i>');

        // Reset counters
        $('#selectedCount').text('0');
        $('#summaryCount').text('0');
        $('#summaryYear').text('-');
        $('#summarySourceClasse').text('-');
        $('#summaryTargetClasse').text('-');
    }

    // Select Year
    $(document).on('click', '.year-card', function() {
        $('.year-card').removeClass('selected');
        $(this).addClass('selected');

        selectedYear = {
            id: $(this).data('id'),
            name: $(this).data('name')
        };

        $('#btnNextStep').prop('disabled', false);
    });

    // Select Source Class
    $(document).on('click', '.classe-card-assign', function() {
        $('.classe-card-assign').removeClass('selected');
        $(this).addClass('selected');

        sourceClasse = {
            id: $(this).data('id'),
            name: $(this).data('name')
        };

        $('#btnNextStep').prop('disabled', false);
    });

    // Select Student
    $(document).on('click', '.student-item', function() {
        let studentId = $(this).data('id');
        let studentName = $(this).data('name');
        let checkbox = $(this).find('.student-checkbox');

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            checkbox.prop('checked', false);
            selectedStudents = selectedStudents.filter(s => s.id !== studentId);
        } else {
            $(this).addClass('selected');
            checkbox.prop('checked', true);
            selectedStudents.push({ id: studentId, name: studentName });
        }

        updateStudentCount();
    });

    // Select All Students
    $('#selectAllStudents').on('click', function() {
        selectedStudents = [];
        $('.student-item').each(function() {
            $(this).addClass('selected');
            $(this).find('.student-checkbox').prop('checked', true);
            selectedStudents.push({
                id: $(this).data('id'),
                name: $(this).data('name')
            });
        });
        updateStudentCount();
    });

    // Deselect All Students
    $('#deselectAllStudents').on('click', function() {
        selectedStudents = [];
        $('.student-item').removeClass('selected');
        $('.student-checkbox').prop('checked', false);
        updateStudentCount();
    });

    function updateStudentCount() {
        $('#selectedCount').text(selectedStudents.length);
        $('#btnNextStep').prop('disabled', selectedStudents.length === 0);
    }

    // Search Students (filter locally)
    $('#searchStudents').on('input', function() {
        let query = $(this).val().toLowerCase();
        if (!query) {
            renderStudents(allStudents);
            return;
        }

        let filtered = allStudents.filter(function(student) {
            return student.fullname.toLowerCase().includes(query) ||
                   (student.nni && student.nni.toLowerCase().includes(query));
        });
        renderStudents(filtered);
    });

    // Next Step
    $('#btnNextStep').on('click', function() {
        if (currentStep === 4) {
            submitAssignment();
            return;
        }

        currentStep++;
        updateStepper();

        if (currentStep === 2) {
            loadClassesByYear();
        } else if (currentStep === 3) {
            loadStudentsFromClass();
        } else if (currentStep === 4) {
            showSummary();
        }
    });

    // Previous Step
    $('#btnPrevStep').on('click', function() {
        if (currentStep === 1) return;

        currentStep--;
        updateStepper();
    });

    function updateStepper() {
        // Update stepper items
        $('.stepper-item').each(function() {
            let step = $(this).data('step');
            $(this).removeClass('active completed');

            if (step < currentStep) {
                $(this).addClass('completed');
            } else if (step === currentStep) {
                $(this).addClass('active');
            }
        });

        // Update step content
        $('.step-content').removeClass('active');
        $('#step' + currentStep).addClass('active');

        // Update navigation buttons
        $('#btnPrevStep').css('visibility', currentStep > 1 ? 'visible' : 'hidden');

        if (currentStep === 4) {
            $('#btnNextStep').html('<i class="mdi mdi-check"></i> Confirmer le transfert');
        } else {
            $('#btnNextStep').html('Suivant <i class="mdi mdi-arrow-right"></i>');
        }

        // Disable next button based on selection
        if (currentStep === 1) {
            $('#btnNextStep').prop('disabled', !selectedYear);
        } else if (currentStep === 2) {
            $('#btnNextStep').prop('disabled', !sourceClasse);
        } else if (currentStep === 3) {
            $('#btnNextStep').prop('disabled', selectedStudents.length === 0);
        } else {
            $('#btnNextStep').prop('disabled', false);
        }
    }

    function loadClassesByYear() {
        $('#classesListAssign').html('');
        $('#loadingClasses2').show();
        $('#noClasses2').hide();
        sourceClasse = null;
        $('#btnNextStep').prop('disabled', true);

        $.ajax({
            url: SITEURL + '/admin/anneescolaires/get-classes',
            type: 'GET',
            data: {
                annee_id: selectedYear.id
            },
            success: function(response) {
                $('#loadingClasses2').hide();

                if (response.classes && response.classes.length > 0) {
                    let html = '';
                    response.classes.forEach(function(classe) {
                        html += `
                            <div class="selection-card classe-card-assign" data-id="${classe.id}" data-name="${classe.nom}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon me-3">
                                            <i class="mdi mdi-school"></i>
                                        </div>
                                        <div>
                                            <div class="card-title">${classe.nom}</div>
                                            <div class="card-subtitle">${classe.niveau} - ${classe.specialite}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#classesListAssign').html(html);
                } else {
                    $('#noClasses2').show();
                }
            },
            error: function() {
                $('#loadingClasses2').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des classes', 'error');
            }
        });
    }

    function loadStudentsFromClass() {
        $('#studentsList').html('');
        $('#loadingStudents').show();
        $('#noStudents').hide();
        selectedStudents = [];
        updateStudentCount();

        $.ajax({
            url: SITEURL + '/admin/classes/get-students',
            type: 'GET',
            data: {
                classe_id: sourceClasse.id
            },
            success: function(response) {
                $('#loadingStudents').hide();

                if (response.students && response.students.length > 0) {
                    allStudents = response.students;
                    renderStudents(response.students);
                } else {
                    $('#noStudents').show();
                }
            },
            error: function() {
                $('#loadingStudents').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des etudiants', 'error');
            }
        });
    }

    function renderStudents(students) {
        let html = '';
        students.forEach(function(student) {
            let initials = student.fullname ? student.fullname.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '??';
            let isSelected = selectedStudents.some(s => s.id === student.id);

            html += `
                <div class="student-item ${isSelected ? 'selected' : ''}" data-id="${student.id}" data-name="${student.fullname || student.name}">
                    <div class="student-avatar">${initials}</div>
                    <div class="student-info">
                        <div class="student-name">${student.fullname || student.name}</div>
                        <div class="student-details">
                            <span><i class="mdi mdi-card-account-details"></i> ${student.nni || '-'}</span>
                            ${student.phone ? `<span class="ms-2"><i class="mdi mdi-phone"></i> ${student.phone}</span>` : ''}
                        </div>
                    </div>
                    <input type="checkbox" class="student-checkbox" ${isSelected ? 'checked' : ''}>
                </div>
            `;
        });
        $('#studentsList').html(html);
    }

    function showSummary() {
        $('#summaryTargetClasse').text(targetClasse.name);
        $('#summaryYear').text(selectedYear.name);
        $('#summarySourceClasse').text(sourceClasse.name);
        $('#summaryCount').text(selectedStudents.length);

        let html = '';
        selectedStudents.forEach(function(student) {
            html += `
                <span class="selected-student-badge">
                    ${student.name}
                    <i class="mdi mdi-close remove-student" data-id="${student.id}"></i>
                </span>
            `;
        });
        $('#selectedStudentsPreview').html(html);
    }

    // Remove student from preview
    $(document).on('click', '.remove-student', function(e) {
        e.stopPropagation();
        let studentId = $(this).data('id');
        selectedStudents = selectedStudents.filter(s => s.id !== studentId);
        $(this).parent().remove();
        $('#summaryCount').text(selectedStudents.length);

        if (selectedStudents.length === 0) {
            $('#btnNextStep').prop('disabled', true);
        }
    });

    function submitAssignment() {
        if (selectedStudents.length === 0) {
            Swal.fire('Attention!', 'Veuillez selectionner au moins un etudiant', 'warning');
            return;
        }

        $('#btnNextStep').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Transfert en cours...');

        $.ajax({
            url: SITEURL + '/admin/anneescolaires/{{ $anneescolaire->id }}/assign-students',
            type: 'POST',
            data: {
                classe_id: targetClasse.id,
                students: selectedStudents.map(s => s.id)
            },
            success: function(response) {
                if (response.success) {
                    $('#assignStudentsModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succes!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                    $('#btnNextStep').prop('disabled', false).html('<i class="mdi mdi-check"></i> Confirmer le transfert');
                }
            },
            error: function(xhr) {
                let message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Erreur!', message, 'error');
                $('#btnNextStep').prop('disabled', false).html('<i class="mdi mdi-check"></i> Confirmer le transfert');
            }
        });
    }

    // Reset assign modal on close
    $('#assignStudentsModal').on('hidden.bs.modal', function() {
        resetAssignModal();
    });
});
</script>
@endsection
