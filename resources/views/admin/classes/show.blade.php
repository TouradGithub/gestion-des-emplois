@extends('layouts.masters.master')

@section('title')
    {{ __('Classe') }} - {{ $class->nom }}
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .class-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: #fff;
        margin-bottom: 25px;
    }
    .class-header h2 {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .class-header .badge-info {
        background: rgba(255,255,255,0.2);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 10px;
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
    .emplois-section {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-clone {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-clone:hover {
        transform: scale(1.05);
        color: #fff;
        box-shadow: 0 5px 20px rgba(17, 153, 142, 0.4);
    }
    .btn-clone-students {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-clone-students:hover {
        transform: scale(1.05);
        color: #fff;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    .student-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 0;
    }
    .student-modal .modal-content {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    .student-modal .student-checkbox {
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    .student-modal .student-checkbox:hover {
        background: #e9ecef;
    }
    .student-modal .student-checkbox.selected {
        background: #d4edda;
        border-left: 3px solid #28a745;
    }
    .student-modal .form-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-left: 0;
        margin: 0;
    }
    .student-modal .form-check-input {
        width: 18px;
        height: 18px;
        min-width: 18px;
        margin: 0;
        flex-shrink: 0;
        cursor: pointer;
    }
    .student-modal .form-check-label {
        flex: 1;
        cursor: pointer;
        margin: 0;
        padding-left: 0;
    }
    .student-modal .student-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .student-modal .student-name {
        font-weight: 600;
        color: #333;
    }
    .student-modal .student-details {
        color: #666;
        font-size: 0.85rem;
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
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    /* Trimester Tabs */
    .trimester-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .trimester-tab {
        padding: 10px 20px;
        border-radius: 25px;
        background: #f0f0f0;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }
    .trimester-tab:hover {
        background: #e0e0e0;
    }
    .trimester-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }
    .trimester-tab .badge {
        margin-left: 5px;
    }

    /* Day Tabs */
    .day-tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }
    .day-tab {
        padding: 8px 16px;
        border-radius: 8px 8px 0 0;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        border: 1px solid #e9ecef;
        border-bottom: none;
    }
    .day-tab:hover {
        background: #e9ecef;
    }
    .day-tab.active {
        background: #667eea;
        color: #fff;
        border-color: #667eea;
    }

    /* Schedule Grid */
    .schedule-container {
        display: flex;
        gap: 20px;
        min-height: 400px;
    }
    .horaires-column {
        width: 100px;
        flex-shrink: 0;
    }
    .horaire-slot {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
        font-weight: 600;
        color: #667eea;
        font-size: 0.9rem;
    }
    .emplois-column {
        flex-grow: 1;
    }
    .emploi-slot {
        height: 80px;
        margin-bottom: 10px;
        border-radius: 10px;
        padding: 10px 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.3s;
    }
    .emploi-slot.empty {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
    }
    .emploi-slot.filled {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .emploi-slot .subject-name {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 3px;
    }
    .emploi-slot .emploi-details {
        font-size: 0.8rem;
        opacity: 0.9;
    }
    .emploi-slot .emploi-details i {
        margin-right: 3px;
    }

    /* Clone Modal */
    .clone-modal .modal-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        border-radius: 0;
    }
    .clone-modal .modal-content {
        border-radius: 15px;
        border: none;
        overflow: hidden;
    }
    .clone-modal .emploi-checkbox {
        padding: 12px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s;
        overflow: visible;
    }
    .clone-modal .emploi-checkbox:hover {
        background: #e9ecef;
    }
    .clone-modal .emploi-checkbox.selected {
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
        position: relative;
    }
    .clone-modal .form-check-label {
        flex: 1;
        cursor: pointer;
        margin: 0;
        padding-left: 0;
    }
    .clone-modal .emploi-info {
        flex: 1;
        min-width: 150px;
    }
    .clone-modal .emploi-info strong {
        color: #333;
        font-size: 0.95rem;
    }
    .clone-modal .emploi-badges {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        align-items: center;
    }
    .clone-modal .emploi-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        gap: 10px;
    }
    .clone-modal .subject-name {
        font-weight: 600;
        color: #333;
        margin-right: 8px;
    }
    .clone-modal .teacher-name {
        color: #666;
        font-size: 0.85rem;
        margin-left: 10px;
    }
    .clone-modal .teacher-name i {
        margin-right: 3px;
    }
    .modal .select2-container {
        width: 100% !important;
    }
    .select2-dropdown {
        z-index: 9999 !important;
    }

    /* No emplois for day */
    .no-emplois-day {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 300px;
        color: #adb5bd;
        font-size: 1.1rem;
    }
    .no-emplois-day i {
        font-size: 3rem;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
@php
    // Get unique trimester IDs first, then load the trimesters
    // $trimesterIds = $class->niveau->trimesters->;
    $trimesters =  $class->niveau->trimesters;
    $jours = \App\Models\Jour::orderBy('ordre')->get();
    $horaires = \App\Models\Horaire::orderBy('ordre')->get();
@endphp

<div class="content-wrapper">
    <!-- Class Header -->
    <div class="class-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="mdi mdi-school"></i> {{ $class->nom }}</h2>
                <div class="mt-2">
                    @if($class->specialite)
                        <span class="badge-info"><i class="mdi mdi-book"></i> {{ $class->specialite->name }}</span>
                    @endif
                    @if($class->niveau)
                        <span class="badge-info"><i class="mdi mdi-layers"></i> {{ $class->niveau->nom }}</span>
                    @endif
                    @if($class->annee)
                        <span class="badge-info"><i class="mdi mdi-calendar"></i> {{ $class->annee->annee }}</span>
                    @endif
                </div>
            </div>
            <div>
                @if($class->emplois->count() > 0)
                    <a href="{{ route('web.emplois.showEmploi', $class->id) }}" class="btn btn-light me-2">
                        <i class="mdi mdi-file-pdf-box"></i> PDF
                    </a>
                @endif
                <a href="{{ route('web.classes.edit', $class->id) }}" class="btn btn-warning">
                    <i class="mdi mdi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="icon text-primary"><i class="mdi mdi-account-group"></i></div>
                <div class="number">{{ $class->students->count() }}</div>
                <div class="label">Etudiants</div>
                @if($autresAnnees->count() > 0)
                    <button type="button" class="btn btn-clone-students btn-sm mt-2" id="btnOpenStudentsModal">
                        <i class="mdi mdi-account-plus"></i> Ajouter depuis une autre annee
                    </button>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="icon text-success"><i class="mdi mdi-calendar-clock"></i></div>
                <div class="number">{{ $class->emplois->count() }}</div>
                <div class="label">Seances</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="icon text-info"><i class="mdi mdi-book-open-variant"></i></div>
                <div class="number">{{ $class->emplois->unique('subject_id')->count() }}</div>
                <div class="label">Matieres</div>
            </div>
        </div>
    </div>

    <!-- Emplois Section -->
    <div class="emplois-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title mb-0">
                <i class="mdi mdi-calendar-clock"></i> Emploi du temps
            </h4>
            <div>
                @if($autresAnnees->count() > 0)
                    <button type="button" class="btn btn-clone" id="btnOpenCloneModal">
                        <i class="mdi mdi-content-copy"></i> Cloner depuis une autre annee
                    </button>
                @endif
            </div>
        </div>

        @if($class->emplois->count() > 0 && $trimesters->count() > 0)
            <!-- Trimester Tabs -->
            <div class="trimester-tabs">
                @foreach($trimesters as $index => $trimester)
                    @php
                        $trimesterEmploisCount =  $class->niveau->trimesters->count();
                    @endphp
                    <div class="trimester-tab {{ $index === 0 ? 'active' : '' }}" data-trimester="{{ $trimester->id }}">
                        <i class="mdi mdi-calendar-range"></i> {{ $trimester->name }}
                        <span class="badge bg-light text-dark">{{ $trimesterEmploisCount }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Content for each trimester -->
            @foreach($trimesters as $tIndex => $trimester)
                @php
                    $trimesterEmplois = $class->emplois->where('trimester_id', $trimester->id);
                    $trimesterJours = $trimesterEmplois->pluck('jour_id')->unique();
                @endphp
                <div class="trimester-content {{ $tIndex === 0 ? '' : 'd-none' }}" data-trimester="{{ $trimester->id }}">
                    <!-- Day Tabs -->
                    <div class="day-tabs">
                        @php $firstDay = true; @endphp
                        @foreach($jours as $jour)
                            @if($trimesterJours->contains($jour->id))
                                <div class="day-tab {{ $firstDay ? 'active' : '' }}" data-jour="{{ $jour->id }}" data-trimester="{{ $trimester->id }}">
                                    <i class="mdi mdi-calendar-today"></i> {{ $jour->libelle_fr }}
                                </div>
                                @php $firstDay = false; @endphp
                            @endif
                        @endforeach
                    </div>

                    <!-- Schedule for each day -->
                    @php $firstDayContent = true; @endphp
                    @foreach($jours as $jour)
                        @if($trimesterJours->contains($jour->id))
                            @php
                                $dayEmplois = $trimesterEmplois->where('jour_id', $jour->id);
                            @endphp
                            <div class="day-content {{ $firstDayContent ? '' : 'd-none' }}" data-jour="{{ $jour->id }}" data-trimester="{{ $trimester->id }}">
                                <div class="schedule-container">
                                    <!-- Horaires Column -->
                                    <div class="horaires-column">
                                        @foreach($horaires as $horaire)
                                            @php
                                                $startHour = intval(substr($horaire->start_time, 0, 2));
                                                $endHour = intval(substr($horaire->end_time, 0, 2));
                                            @endphp
                                            <div class="horaire-slot">
                                                {{ $startHour }}h-{{ $endHour }}h
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Emplois Column -->
                                    <div class="emplois-column">
                                        @foreach($horaires as $horaire)
                                            @php
                                                $emploiForHoraire = $dayEmplois->first(function($e) use ($horaire) {
                                                    return $e->ref_horaires->contains('id', $horaire->id);
                                                });
                                            @endphp
                                            @if($emploiForHoraire)
                                                <div class="emploi-slot filled">
                                                    <div class="subject-name">{{ $emploiForHoraire->subject->name ?? '-' }}</div>
                                                    <div class="emploi-details">
                                                        <i class="mdi mdi-account-tie"></i> {{ $emploiForHoraire->teacher->name ?? '-' }}
                                                        @if($emploiForHoraire->salle)
                                                            <span class="ms-2"><i class="mdi mdi-door"></i> {{ $emploiForHoraire->salle->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="emploi-slot empty">
                                                    <span class="text-muted">-</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @php $firstDayContent = false; @endphp
                        @endif
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="mdi mdi-calendar-blank-outline"></i>
                <h5 class="text-muted">Aucune seance programmee</h5>
                <p class="text-muted">Commencez par cloner depuis une autre annee ou ajouter des seances manuellement.</p>
            </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('web.classes.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Retour a la liste
        </a>
    </div>
</div>

<!-- Clone Emplois Modal -->
<div class="modal fade clone-modal" id="cloneEmploisModal" tabindex="-1" aria-labelledby="cloneEmploisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cloneEmploisModalLabel">
                    <i class="mdi mdi-content-copy"></i> Cloner l'emploi du temps
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Select Year -->
                <div class="mb-4">
                    <label class="form-label fw-bold">1. Selectionner l'annee source:</label>
                    <select class="form-select" id="sourceYear">
                        <option value="">-- Choisir une annee --</option>
                        @foreach($autresAnnees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->annee }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted mt-1 d-block">Affichera les classes avec le meme niveau ({{ $class->niveau->nom ?? '-' }}) et specialite ({{ $class->specialite->name ?? '-' }})</small>
                </div>

                <!-- Step 2: Select Class -->
                <div id="classesContainer" style="display: none;">
                    <label class="form-label fw-bold">2. Selectionner la classe source:</label>
                    <select class="form-select" id="sourceClasse">
                        <option value="">-- Choisir une classe --</option>
                    </select>
                </div>

                <!-- Step 3: Select Trimester -->
                <div id="trimesterContainer" class="mt-4" style="display: none;">
                    <label class="form-label fw-bold">3. Selectionner le trimestre:</label>
                    <select class="form-select" id="sourceTrimester">
                        <option value="">-- Tous les trimestres --</option>
                    </select>
                </div>

                <!-- Step 4: Select Emplois -->
                <div id="emploisContainer" class="mt-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold mb-0">4. Selectionner les seances a cloner:</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllEmplois">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Tout
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllEmplois">
                                <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Aucun
                            </button>
                        </div>
                    </div>
                    <div id="emploisList" class="emplois-list" style="max-height: 350px; overflow-y: auto;">
                        <!-- Emplois will be loaded here -->
                    </div>
                </div>

                <!-- Loading -->
                <div id="loadingState" style="display: none; text-align: center; padding: 30px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement...</p>
                </div>

                <!-- Empty States -->
                <div id="noClasses" style="display: none; text-align: center; padding: 20px;">
                    <i class="mdi mdi-alert-circle-outline" style="font-size: 2.5rem; color: #ffc107;"></i>
                    <p class="mt-2 text-muted">Aucune classe correspondante dans cette annee.</p>
                </div>

                <div id="noEmplois" style="display: none; text-align: center; padding: 20px;">
                    <i class="mdi mdi-calendar-blank-outline" style="font-size: 2.5rem; color: #6c757d;"></i>
                    <p class="mt-2 text-muted">Cette classe n'a aucune seance programmee.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCloseModal">Annuler</button>
                <button type="button" class="btn btn-clone" id="btnCloneEmplois" disabled>
                    <i class="mdi mdi-content-copy"></i> Cloner les seances selectionnees
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clone Students Modal -->
<div class="modal fade student-modal" id="cloneStudentsModal" tabindex="-1" aria-labelledby="cloneStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cloneStudentsModalLabel">
                    <i class="mdi mdi-account-plus"></i> Ajouter des etudiants depuis une autre annee
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Select Year -->
                <div class="mb-4">
                    <label class="form-label fw-bold">1. Selectionner l'annee source:</label>
                    <select class="form-select" id="studentSourceYear">
                        <option value="">-- Choisir une annee --</option>
                        @foreach($autresAnnees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->annee }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted mt-1 d-block">Affichera les classes avec la meme specialite ({{ $class->specialite->name ?? '-' }})</small>
                </div>

                <!-- Step 2: Select Class -->
                <div id="studentClassesContainer" style="display: none;">
                    <label class="form-label fw-bold">2. Selectionner la classe source:</label>
                    <select class="form-select" id="studentSourceClasse">
                        <option value="">-- Choisir une classe --</option>
                    </select>
                </div>

                <!-- Step 3: Select Students -->
                <div id="studentsContainer" class="mt-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold mb-0">3. Selectionner les etudiants:</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllStudents">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Tout
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllStudents">
                                <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Aucun
                            </button>
                        </div>
                    </div>
                    <div id="studentsList" class="students-list" style="max-height: 350px; overflow-y: auto;">
                        <!-- Students will be loaded here -->
                    </div>
                </div>

                <!-- Loading -->
                <div id="studentLoadingState" style="display: none; text-align: center; padding: 30px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement...</p>
                </div>

                <!-- Empty States -->
                <div id="noStudentClasses" style="display: none; text-align: center; padding: 20px;">
                    <i class="mdi mdi-alert-circle-outline" style="font-size: 2.5rem; color: #ffc107;"></i>
                    <p class="mt-2 text-muted">Aucune classe correspondante dans cette annee.</p>
                </div>

                <div id="noStudents" style="display: none; text-align: center; padding: 20px;">
                    <i class="mdi mdi-account-off-outline" style="font-size: 2.5rem; color: #6c757d;"></i>
                    <p class="mt-2 text-muted">Cette classe n'a aucun etudiant.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCloseStudentModal">Annuler</button>
                <button type="button" class="btn btn-clone-students" id="btnCloneStudents" disabled>
                    <i class="mdi mdi-account-plus"></i> Ajouter les etudiants selectionnes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const SITEURL = "{{ url('/') }}";
    const currentClasseId = {{ $class->id }};
    let allEmplois = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Trimester tab switching
    $('.trimester-tab').on('click', function() {
        let trimesterId = $(this).data('trimester');

        // Update active tab
        $('.trimester-tab').removeClass('active');
        $(this).addClass('active');

        // Show corresponding content
        $('.trimester-content').addClass('d-none');
        $(`.trimester-content[data-trimester="${trimesterId}"]`).removeClass('d-none');

        // Reset day tabs and show first day
        let $firstDayTab = $(`.day-tab[data-trimester="${trimesterId}"]:first`);
        $(`.day-tab[data-trimester="${trimesterId}"]`).removeClass('active');
        $firstDayTab.addClass('active');

        let firstJourId = $firstDayTab.data('jour');
        $(`.day-content[data-trimester="${trimesterId}"]`).addClass('d-none');
        $(`.day-content[data-trimester="${trimesterId}"][data-jour="${firstJourId}"]`).removeClass('d-none');
    });

    // Day tab switching
    $('.day-tab').on('click', function() {
        let jourId = $(this).data('jour');
        let trimesterId = $(this).data('trimester');

        // Update active tab within same trimester
        $(`.day-tab[data-trimester="${trimesterId}"]`).removeClass('active');
        $(this).addClass('active');

        // Show corresponding content
        $(`.day-content[data-trimester="${trimesterId}"]`).addClass('d-none');
        $(`.day-content[data-trimester="${trimesterId}"][data-jour="${jourId}"]`).removeClass('d-none');
    });

    // Open Clone Modal
    $('#btnOpenCloneModal').on('click', function() {
        resetModal();
        $('#cloneEmploisModal').modal('show');
    });

    // Close modal button
    $('#btnCloseModal').on('click', function() {
        $('#cloneEmploisModal').modal('hide');
    });

    function resetModal() {
        $('#sourceYear').val('');
        $('#sourceClasse').val('').html('<option value="">-- Choisir une classe --</option>');
        $('#sourceTrimester').val('').html('<option value="">-- Tous les trimestres --</option>');
        $('#classesContainer').hide();
        $('#trimesterContainer').hide();
        $('#emploisContainer').hide();
        $('#noClasses').hide();
        $('#noEmplois').hide();
        $('#emploisList').html('');
        $('#btnCloneEmplois').prop('disabled', true).html('<i class="mdi mdi-content-copy"></i> Cloner les seances selectionnees');
        allEmplois = [];
    }

    // Load classes when year is selected
    $('#sourceYear').on('change', function() {
        let anneeId = $(this).val();

        $('#classesContainer').hide();
        $('#trimesterContainer').hide();
        $('#emploisContainer').hide();
        $('#noClasses').hide();

        if (!anneeId) return;

        $('#loadingState').show();

        $.ajax({
            url: SITEURL + '/admin/classes/get-matching-classes',
            type: 'GET',
            data: {
                annee_id: anneeId,
                current_classe_id: currentClasseId
            },
            success: function(response) {
                $('#loadingState').hide();

                if (response.classes && response.classes.length > 0) {
                    let options = '<option value="">-- Choisir une classe --</option>';
                    response.classes.forEach(function(classe) {
                        let emploisBadge = classe.emplois_count > 0
                            ? ` (${classe.emplois_count} seances)`
                            : ' (aucune seance)';
                        options += `<option value="${classe.id}" ${classe.emplois_count === 0 ? 'disabled' : ''}>${classe.nom}${emploisBadge}</option>`;
                    });
                    $('#sourceClasse').html(options);
                    $('#classesContainer').show();
                } else {
                    $('#noClasses').show();
                }
            },
            error: function() {
                $('#loadingState').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des classes', 'error');
            }
        });
    });

    // Load emplois when class is selected
    $('#sourceClasse').on('change', function() {
        let classeId = $(this).val();

        $('#trimesterContainer').hide();
        $('#emploisContainer').hide();
        $('#noEmplois').hide();

        if (!classeId) return;

        $('#loadingState').show();

        $.ajax({
            url: SITEURL + '/admin/classes/get-emplois',
            type: 'GET',
            data: { classe_id: classeId },
            success: function(response) {
                $('#loadingState').hide();

                if (response.emplois && response.emplois.length > 0) {
                    allEmplois = response.emplois;

                    // Extract unique trimesters
                    let trimesters = {};
                    response.emplois.forEach(function(e) {
                        if (e.trimester_id && !trimesters[e.trimester_id]) {
                            trimesters[e.trimester_id] = e.trimester_name;
                        }
                    });

                    // Populate trimester select
                    let trimOptions = '<option value="">-- Tous les trimestres --</option>';
                    for (let id in trimesters) {
                        trimOptions += `<option value="${id}">${trimesters[id]}</option>`;
                    }
                    $('#sourceTrimester').html(trimOptions);
                    $('#trimesterContainer').show();

                    // Render all emplois
                    renderEmplois(response.emplois);
                    $('#emploisContainer').show();
                } else {
                    $('#noEmplois').show();
                }
            },
            error: function() {
                $('#loadingState').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des seances', 'error');
            }
        });
    });

    // Filter by trimester
    $('#sourceTrimester').on('change', function() {
        let trimesterId = $(this).val();

        if (trimesterId) {
            let filtered = allEmplois.filter(e => e.trimester_id == trimesterId);
            renderEmplois(filtered);
        } else {
            renderEmplois(allEmplois);
        }
    });

    function renderEmplois(emplois) {
        if (emplois.length === 0) {
            $('#emploisList').html('<div class="text-center text-muted py-4">Aucune seance pour ce trimestre</div>');
            updateCloneButton();
            return;
        }

        // Group by jour
        let grouped = {};
        emplois.forEach(function(emploi) {
            let jour = emploi.jour_name;
            if (!grouped[jour]) {
                grouped[jour] = [];
            }
            grouped[jour].push(emploi);
        });

        let html = '';
        for (let jour in grouped) {
            html += `<div class="mb-3">
                <div class="bg-primary text-white px-3 py-2 rounded mb-2 fw-bold">
                    <i class="mdi mdi-calendar-today"></i> ${jour}
                </div>`;

            grouped[jour].forEach(function(emploi) {
                let typeDisplay = emploi.subject_type ? `<span class="badge bg-warning text-dark">${emploi.subject_type}</span>` : '';
                html += `
                    <div class="emploi-checkbox" data-id="${emploi.id}">
                        <div class="form-check">
                            <input class="form-check-input emploi-check" type="checkbox" value="${emploi.id}" id="emploi_${emploi.id}">
                            <label class="form-check-label" for="emploi_${emploi.id}">
                                <div class="emploi-row">
                                    <div class="emploi-info">
                                        <span class="subject-name">${emploi.subject_name}</span>
                                        ${typeDisplay}
                                        <span class="teacher-name"><i class="mdi mdi-account"></i> ${emploi.teacher_name}</span>
                                    </div>
                                    <div class="emploi-badges">
                                        <span class="badge bg-success">${emploi.horaires}</span>
                                        <span class="badge bg-info">${emploi.trimester_name}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
        }

        $('#emploisList').html(html);
        updateCloneButton();
    }

    // Handle checkbox change
    $(document).on('change', '.emploi-check', function() {
        let $parent = $(this).closest('.emploi-checkbox');
        if ($(this).is(':checked')) {
            $parent.addClass('selected');
        } else {
            $parent.removeClass('selected');
        }
        updateCloneButton();
    });

    // Select all
    $('#selectAllEmplois').on('click', function() {
        $('.emploi-check').prop('checked', true);
        $('.emploi-checkbox').addClass('selected');
        updateCloneButton();
    });

    // Deselect all
    $('#deselectAllEmplois').on('click', function() {
        $('.emploi-check').prop('checked', false);
        $('.emploi-checkbox').removeClass('selected');
        updateCloneButton();
    });

    function updateCloneButton() {
        let checkedCount = $('.emploi-check:checked').length;
        $('#btnCloneEmplois').prop('disabled', checkedCount === 0);
        if (checkedCount > 0) {
            $('#btnCloneEmplois').html(`<i class="mdi mdi-content-copy"></i> Cloner ${checkedCount} seance(s)`);
        } else {
            $('#btnCloneEmplois').html('<i class="mdi mdi-content-copy"></i> Cloner les seances selectionnees');
        }
    }

    // Clone emplois
    $('#btnCloneEmplois').on('click', function() {
        let selectedIds = [];
        $('.emploi-check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire('Attention!', 'Veuillez selectionner au moins une seance', 'warning');
            return;
        }

        $(this).prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Clonage en cours...');

        $.ajax({
            url: SITEURL + '/admin/classes/' + currentClasseId + '/clone-emplois',
            type: 'POST',
            data: { emplois: selectedIds },
            success: function(response) {
                if (response.success) {
                    $('#cloneEmploisModal').modal('hide');

                    let message = response.message;
                    let icon = 'success';

                    if (response.conflicts && response.conflicts.length > 0) {
                        message += '<br><br><strong>Avertissements:</strong><br>';
                        response.conflicts.forEach(function(c) {
                            message += '- ' + c + '<br>';
                        });
                        icon = 'warning';
                    }

                    Swal.fire({
                        icon: icon,
                        title: response.cloned_count > 0 ? 'Succes!' : 'Attention!',
                        html: message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (response.cloned_count > 0) {
                            location.reload();
                        }
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
    $('#cloneEmploisModal').on('hidden.bs.modal', function() {
        resetModal();
    });

    // ========== STUDENTS MODAL ==========

    // Open Students Modal
    $('#btnOpenStudentsModal').on('click', function() {
        resetStudentModal();
        $('#cloneStudentsModal').modal('show');
    });

    // Close student modal button
    $('#btnCloseStudentModal').on('click', function() {
        $('#cloneStudentsModal').modal('hide');
    });

    function resetStudentModal() {
        $('#studentSourceYear').val('');
        $('#studentSourceClasse').val('').html('<option value="">-- Choisir une classe --</option>');
        $('#studentClassesContainer').hide();
        $('#studentsContainer').hide();
        $('#noStudentClasses').hide();
        $('#noStudents').hide();
        $('#studentsList').html('');
        $('#btnCloneStudents').prop('disabled', true).html('<i class="mdi mdi-account-plus"></i> Ajouter les etudiants selectionnes');
    }

    // Load classes when year is selected (for students)
    $('#studentSourceYear').on('change', function() {
        let anneeId = $(this).val();

        $('#studentClassesContainer').hide();
        $('#studentsContainer').hide();
        $('#noStudentClasses').hide();

        if (!anneeId) return;

        $('#studentLoadingState').show();

        $.ajax({
            url: SITEURL + '/admin/classes/get-classes-for-students',
            type: 'GET',
            data: {
                annee_id: anneeId,
                current_classe_id: currentClasseId
            },
            success: function(response) {
                $('#studentLoadingState').hide();

                if (response.classes && response.classes.length > 0) {
                    let options = '<option value="">-- Choisir une classe --</option>';
                    response.classes.forEach(function(classe) {
                        let studentsBadge = classe.students_count > 0
                            ? ` (${classe.students_count} etudiants)`
                            : ' (aucun etudiant)';
                        options += `<option value="${classe.id}" ${classe.students_count === 0 ? 'disabled' : ''}>${classe.nom} - ${classe.niveau}${studentsBadge}</option>`;
                    });
                    $('#studentSourceClasse').html(options);
                    $('#studentClassesContainer').show();
                } else {
                    $('#noStudentClasses').show();
                }
            },
            error: function() {
                $('#studentLoadingState').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des classes', 'error');
            }
        });
    });

    // Load students when class is selected
    $('#studentSourceClasse').on('change', function() {
        let classeId = $(this).val();

        $('#studentsContainer').hide();
        $('#noStudents').hide();

        if (!classeId) return;

        $('#studentLoadingState').show();

        $.ajax({
            url: SITEURL + '/admin/classes/get-students',
            type: 'GET',
            data: { classe_id: classeId },
            success: function(response) {
                $('#studentLoadingState').hide();

                if (response.students && response.students.length > 0) {
                    renderStudents(response.students);
                    $('#studentsContainer').show();
                } else {
                    $('#noStudents').show();
                }
            },
            error: function() {
                $('#studentLoadingState').hide();
                Swal.fire('Erreur!', 'Erreur lors du chargement des etudiants', 'error');
            }
        });
    });

    function renderStudents(students) {
        let html = '';
        students.forEach(function(student) {
            html += `
                <div class="student-checkbox" data-id="${student.id}">
                    <div class="form-check">
                        <input class="form-check-input student-check" type="checkbox" value="${student.id}" id="student_${student.id}">
                        <label class="form-check-label" for="student_${student.id}">
                            <div class="student-info">
                                <span class="student-name"><i class="mdi mdi-account"></i> ${student.fullname}</span>
                                <span class="student-details">
                                    ${student.nni ? '<span class="badge bg-secondary me-1">' + student.nni + '</span>' : ''}
                                    ${student.phone ? '<span class="badge bg-info">' + student.phone + '</span>' : ''}
                                </span>
                            </div>
                        </label>
                    </div>
                </div>
            `;
        });
        $('#studentsList').html(html);
        updateStudentButton();
    }

    // Handle student checkbox change
    $(document).on('change', '.student-check', function() {
        let $parent = $(this).closest('.student-checkbox');
        if ($(this).is(':checked')) {
            $parent.addClass('selected');
        } else {
            $parent.removeClass('selected');
        }
        updateStudentButton();
    });

    // Select all students
    $('#selectAllStudents').on('click', function() {
        $('.student-check').prop('checked', true);
        $('.student-checkbox').addClass('selected');
        updateStudentButton();
    });

    // Deselect all students
    $('#deselectAllStudents').on('click', function() {
        $('.student-check').prop('checked', false);
        $('.student-checkbox').removeClass('selected');
        updateStudentButton();
    });

    function updateStudentButton() {
        let checkedCount = $('.student-check:checked').length;
        $('#btnCloneStudents').prop('disabled', checkedCount === 0);
        if (checkedCount > 0) {
            $('#btnCloneStudents').html(`<i class="mdi mdi-account-plus"></i> Ajouter ${checkedCount} etudiant(s)`);
        } else {
            $('#btnCloneStudents').html('<i class="mdi mdi-account-plus"></i> Ajouter les etudiants selectionnes');
        }
    }

    // Clone students
    $('#btnCloneStudents').on('click', function() {
        let selectedIds = [];
        $('.student-check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            Swal.fire('Attention!', 'Veuillez selectionner au moins un etudiant', 'warning');
            return;
        }

        $(this).prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Ajout en cours...');

        $.ajax({
            url: SITEURL + '/admin/classes/' + currentClasseId + '/clone-students',
            type: 'POST',
            data: { students: selectedIds },
            success: function(response) {
                if (response.success) {
                    $('#cloneStudentsModal').modal('hide');

                    let message = response.message;
                    let icon = 'success';

                    if (response.skipped_duplicates && response.skipped_duplicates.length > 0) {
                        icon = 'warning';
                    }

                    Swal.fire({
                        icon: icon,
                        title: response.cloned_count > 0 ? 'Succes!' : 'Attention!',
                        html: message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (response.cloned_count > 0) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                    updateStudentButton();
                }
            },
            error: function(xhr) {
                let message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Erreur!', message, 'error');
                updateStudentButton();
            }
        });
    });

    // Reset student modal on close
    $('#cloneStudentsModal').on('hidden.bs.modal', function() {
        resetStudentModal();
    });
});
</script>
@endsection
