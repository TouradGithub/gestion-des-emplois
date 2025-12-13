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
                        <div class="classe-card">
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
});
</script>
@endsection
