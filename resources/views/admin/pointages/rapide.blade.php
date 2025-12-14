@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.pointage_rapide') }}
@endsection

@section('css')
<style>
    .filter-card {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .table-pointage {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .table-pointage th {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        font-weight: 600;
        padding: 12px;
        border: none;
    }
    .table-pointage td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
    }
    .table-pointage tbody tr:hover {
        background-color: #f8f9fa;
    }
    .btn-statut {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .btn-present {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .btn-present.active, .btn-present:hover {
        background: #2e7d32;
        color: #fff;
    }
    .btn-absent {
        background: #ffebee;
        color: #c62828;
    }
    .btn-absent.active, .btn-absent:hover {
        background: #c62828;
        color: #fff;
    }
    .summary-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 20px;
    }
    .count-badge {
        font-size: 1.2rem;
        padding: 8px 16px;
        border-radius: 10px;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #11998e;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
    }
    .empty-state i {
        font-size: 80px;
        color: #ccc;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-spinner"></div>
    </div>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-clock-fast"></i>
            </span>
            {{ __('pointages.pointage_rapide') }}
        </h3>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="mdi mdi-calendar"></i> {{ __('pointages.date') }}
                </label>
                <input type="date" id="date_pointage" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <button type="button" id="btn_charger" class="btn btn-success">
                    <i class="mdi mdi-magnify"></i> {{ __('pointages.charger') }}
                </button>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="marquerTousPresents()">
                        <i class="mdi mdi-check-all"></i> {{ __('pointages.tous_presents') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="marquerTousAbsents()">
                        <i class="mdi mdi-close-circle-multiple"></i> {{ __('pointages.tous_absents') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div id="table_container" style="display: none;">
        <form id="pointageForm">
            <input type="hidden" name="date_pointage" id="form_date_pointage">

            <div class="table-pointage">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 25%">{{ __('pointages.professeur') }}</th>
                            <th style="width: 20%">{{ __('pointages.classe') }}</th>
                            <th style="width: 25%">{{ __('pointages.matiere') }}</th>
                            <th style="width: 15%">{{ __('pointages.horaires') }}</th>
                            <th style="width: 10%">{{ __('pointages.statut') }}</th>
                        </tr>
                    </thead>
                    <tbody id="pointages_tbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="summary-card">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-2">{{ __('pointages.resume') }}</h6>
                        <div class="d-flex gap-3">
                            <span class="badge bg-success count-badge">
                                <span id="count_presents">0</span> {{ __('pointages.present') }}
                            </span>
                            <span class="badge bg-danger count-badge">
                                <span id="count_absents">0</span> {{ __('pointages.absent') }}
                            </span>
                            <span class="badge bg-secondary count-badge">
                                <span id="count_total">0</span> Total
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" id="btn_save" class="btn btn-success btn-lg">
                            <i class="mdi mdi-content-save"></i> {{ __('pointages.enregistrer') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Empty State -->
    <div id="empty_state" class="empty-state" style="display: none;">
        <i class="mdi mdi-calendar-remove"></i>
        <h5 class="mt-3 text-muted">{{ __('pointages.aucun_cours_programme') }}</h5>
    </div>

    <!-- Initial State -->
    <div id="initial_state" class="empty-state">
        <i class="mdi mdi-calendar-search"></i>
        <h5 class="mt-3 text-muted">{{ __('pointages.selectionnez_date') }}</h5>
        <p class="text-muted">{{ __('pointages.selectionnez_date_desc') }}</p>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    const SITEURL = "{{ url('/') }}";
    let emploisData = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Charger les données
    $('#btn_charger').on('click', function() {
        loadData();
    });

    // Charger au changement de date
    $('#date_pointage').on('change', function() {
        loadData();
    });

    function loadData() {
        let datePointage = $('#date_pointage').val();
        if (!datePointage) {
            alert('{{ __("Veuillez sélectionner une date") }}');
            return;
        }

        $('#loadingOverlay').show();
        $('#initial_state').hide();
        $('#empty_state').hide();
        $('#table_container').hide();

        $.ajax({
            url: SITEURL + '/admin/pointages/rapide/data',
            type: 'GET',
            data: { date: datePointage },
            success: function(response) {
                $('#loadingOverlay').hide();

                if (response.emplois && response.emplois.length > 0) {
                    emploisData = response.emplois;
                    renderTable(response.emplois, response.pointages_existants || []);
                    $('#form_date_pointage').val(datePointage);
                    $('#table_container').show();
                    $('#btn_export_pdf').show();
                } else {
                    $('#empty_state').show();
                    $('#btn_export_pdf').hide();
                }
            },
            error: function() {
                $('#loadingOverlay').hide();
                alert('{{ __("Erreur lors du chargement des données") }}');
            }
        });
    }

    function renderTable(emplois, pointagesExistants) {
        let html = '';

        emplois.forEach(function(emploi, index) {
            let existant = pointagesExistants.find(p => p.emploi_temps_id == emploi.id);
            let statut = existant ? existant.statut : 'absent'; // Absent par défaut

            html += `
                <tr data-emploi-id="${emploi.id}">
                    <td>${index + 1}</td>
                    <td>
                        <strong>${emploi.teacher_name}</strong>
                    </td>
                    <td>
                        <span class="badge bg-info">${emploi.classe_name}</span>
                    </td>
                    <td>${emploi.subject_name}</td>
                    <td>
                        <small class="text-muted">${emploi.horaires}</small>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button"
                                    class="btn-statut btn-present ${statut === 'present' ? 'active' : ''}"
                                    data-emploi="${emploi.id}"
                                    data-statut="present">
                                <i class="mdi mdi-check"></i>
                            </button>
                            <button type="button"
                                    class="btn-statut btn-absent ${statut === 'absent' ? 'active' : ''}"
                                    data-emploi="${emploi.id}"
                                    data-statut="absent">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        <input type="hidden" name="pointages[${index}][emploi_temps_id]" value="${emploi.id}">
                        <input type="hidden" name="pointages[${index}][statut]" value="${statut}" class="statut-input" data-emploi="${emploi.id}">
                    </td>
                </tr>
            `;
        });

        $('#pointages_tbody').html(html);
        updateCounts();
        bindStatutButtons();
    }

    function bindStatutButtons() {
        $('.btn-statut').off('click').on('click', function() {
            let emploiId = $(this).data('emploi');
            let statut = $(this).data('statut');

            // Update buttons
            $(`.btn-statut[data-emploi="${emploiId}"]`).removeClass('active');
            $(this).addClass('active');

            // Update hidden input
            $(`.statut-input[data-emploi="${emploiId}"]`).val(statut);

            // Update row style
            let row = $(this).closest('tr');
            row.removeClass('table-success table-danger');
            if (statut === 'present') {
                row.addClass('table-success');
            } else {
                row.addClass('table-danger');
            }

            updateCounts();
        });
    }

    window.marquerTousPresents = function() {
        $('.btn-statut[data-statut="present"]').click();
    };

    window.marquerTousAbsents = function() {
        $('.btn-statut[data-statut="absent"]').click();
    };

    function updateCounts() {
        let presents = $('.statut-input[value="present"]').length;
        let absents = $('.statut-input[value="absent"]').length;
        let total = $('.statut-input').length;

        $('#count_presents').text(presents);
        $('#count_absents').text(absents);
        $('#count_total').text(total);
    }

    // Save
    $('#btn_save').on('click', function() {
        let datePointage = $('#form_date_pointage').val();
        let pointages = [];

        $('.statut-input').each(function() {
            pointages.push({
                emploi_temps_id: $(this).data('emploi'),
                statut: $(this).val()
            });
        });

        if (pointages.length === 0) {
            alert('{{ __("Aucun pointage à enregistrer") }}');
            return;
        }

        $('#loadingOverlay').show();

        $.ajax({
            url: SITEURL + '/admin/pointages/rapide/store',
            type: 'POST',
            data: {
                date_pointage: datePointage,
                pointages: pointages
            },
            success: function(response) {
                $('#loadingOverlay').hide();
                if (response.success) {
                    alert(response.message || '{{ __("Pointages enregistrés avec succès") }}');
                    loadData(); // Reload
                } else {
                    alert(response.message || '{{ __("Erreur lors de l\'enregistrement") }}');
                }
            },
            error: function(xhr) {
                $('#loadingOverlay').hide();
                alert('{{ __("Erreur lors de l\'enregistrement") }}');
            }
        });
    });

    // Auto-load today's data
    loadData();

    // Export PDF function
  
});
</script>
@endsection
