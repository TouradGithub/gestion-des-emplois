@extends('layouts.masters.master')

@section('title')
    {{ __('Emplois du Temps') }}
@endsection

@section('css')
<style>
    .stats-card {
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 2px solid #e0e0e0;
        background: #fff;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #1a1a1a;
    }
    .stats-card .icon {
        font-size: 3rem;
        color: #1a1a1a;
        opacity: 0.7;
    }
    .stats-card .number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    .stats-card .label {
        font-size: 0.95rem;
        color: #666;
    }
    .stats-card.card-1 {
        border-left: 4px solid #1a1a1a;
    }
    .stats-card.card-2 {
        border-left: 4px solid #666;
    }
    .stats-card.card-3 {
        border-left: 4px solid #999;
    }
    .stats-card.card-4 {
        border-left: 4px solid #333;
    }
    .filter-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        border: 1px solid #e0e0e0;
    }
    .filter-card .form-control, .filter-card .form-select {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 10px 15px;
    }
    .filter-card .form-control:focus, .filter-card .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .main-card {
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
    }
    .main-card .card-body {
        padding: 25px;
    }
    .btn-action-group .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        margin-right: 10px;
        transition: all 0.3s;
    }
    .btn-action-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        background:   #06a465 ;
        background: ;
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 15px;
    }
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }
    .badge-jour {
        background: #1a1a1a;
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
    }
    .badge-horaire {
        background: #fff;
        color: #1a1a1a;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 2px;
        display: inline-block;
        border: 1px solid #1a1a1a;
    }
    .badge-salle {
        background: #f5f5f5;
        color: #1a1a1a;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid #ccc;
    }
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
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 80px;
        color: #ccc;
        margin-bottom: 20px;
    }
    .quick-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .quick-actions .btn-light {
        background: #fff;
        color: #1a1a1a;
        border: none;
    }
    .quick-actions .btn-light:hover {
        background: #f5f5f5;
    }
    .quick-actions .btn-outline-light {
        background: transparent;
        color: #fff;
        border: 2px solid #fff;
    }
    .quick-actions .btn-outline-light:hover {
        background: #fff;
        color: #1a1a1a;
    }
    .btn-gradient-primary {
        background: #1a1a1a;
        color: #fff;
        border: none;
    }
    .btn-gradient-primary:hover {
        background: #333;
        color: #fff;
    }
    .form-label {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><i class="mdi mdi-calendar-clock me-2"></i> Gestion des Emplois du Temps</h3>
                <p>Planifiez et gérez les cours de vos classes</p>
            </div>
            <div class="quick-actions">
                <a href="{{ route('web.emplois.create') }}" class="btn btn-light">
                    <i class="mdi mdi-plus-circle"></i> Nouvelle séance
                </a>
                <a href="{{ route('web.emplois.showCalender') }}" class="btn btn-outline-light">
                    <i class="mdi mdi-calendar"></i> Vue calendrier
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card card-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number" id="stat-total">-</div>
                        <div class="label">Total des séances</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-calendar-multiple"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card card-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number" id="stat-classes">-</div>
                        <div class="label">Classes actives</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-account-group"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card card-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number" id="stat-teachers">-</div>
                        <div class="label">Enseignants</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-account-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card card-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number" id="stat-subjects">-</div>
                        <div class="label">Matières</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-book-open-variant"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-account-group"></i> Classe</label>
                <select id="filter_class" class="form-select">
                    <option value="">Toutes les classes</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-account-tie"></i> Enseignant</label>
                <select id="filter_teacher" class="form-select">
                    <option value="">Tous les enseignants</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-today"></i> Jour</label>
                <select id="filter_jour" class="form-select">
                    <option value="">Tous les jours</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-range"></i> Trimestre</label>
                <select id="filter_trimester" class="form-select">
                    <option value="">Tous</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" id="btn_filter" class="btn btn-gradient-primary w-100">
                    <i class="mdi mdi-filter"></i> Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card main-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="table_list"
                               data-toggle="table"
                               data-url="{{ route('web.emplois.list') }}"
                               data-side-pagination="server"
                               data-pagination="true"
                               data-page-list="[10, 25, 50, 100]"
                               data-page-size="10"
                               data-search="true"
                               data-search-placeholder="Rechercher..."
                               data-show-refresh="true"
                               data-show-columns="true"
                               data-show-export="true"
                               data-sort-name="id"
                               data-sort-order="desc"
                               data-query-params="queryParams"
                               data-loading-template="loadingTemplate">
                            <thead>
                                <tr>
                                    <th data-field="id" data-visible="false">#</th>
                                    <th data-field="no" data-width="60">N°</th>
                                    <th data-field="class" data-sortable="true">Classe</th>
                                    <th data-field="subject" data-sortable="true">Matière</th>
                                    <th data-field="teacher" data-sortable="true">Enseignant</th>
                                    <th data-field="jour" data-sortable="true">Jour</th>
                                    <th data-field="horaire">Horaires</th>
                                    <th data-field="salle">Salle</th>
                                    <th data-field="trimester" data-sortable="true">Trimestre</th>
                                    <th data-field="operate" data-events="actionEvents" data-sortable="false" data-width="120">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #1a1a1a; color: #fff;">
                <h5 class="modal-title"><i class="mdi mdi-alert-circle"></i> Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="mdi mdi-delete-alert" style="font-size: 60px; color: #666;"></i>
                <h5 class="mt-3">Voulez-vous vraiment supprimer cette séance ?</h5>
                <p class="text-muted">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn" style="background: #1a1a1a; color: #fff;" id="confirmDelete">
                    <i class="mdi mdi-delete"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let deleteRowId = null;

    // Loading template
    function loadingTemplate() {
        return '<div class="text-center py-5"><div class="spinner-border" style="color: #1a1a1a;" role="status"><span class="visually-hidden">Chargement...</span></div><p class="mt-2 text-muted">Chargement des données...</p></div>';
    }

    // Query params for table
    function queryParams(p) {
        return {
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search,
            class_id: $('#filter_class').val(),
            teacher_id: $('#filter_teacher').val(),
            jour_id: $('#filter_jour').val(),
            trimester_id: $('#filter_trimester').val()
        };
    }

    // Action events
    window.actionEvents = {
        'click .editdata': function (e, value, row, index) {
            window.location.href = `{{ url('/admin/emplois') }}/${row.id}/edit`;
        },
        'click .deletedata': function (e, value, row, index) {
            deleteRowId = row.id;
            $('#deleteModal').modal('show');
        }
    };

    $(document).ready(function() {
        // Load statistics
        loadStats();

        // Load filters data
        loadFiltersData();

        // Filter button click
        $('#btn_filter').on('click', function() {
            $('#table_list').bootstrapTable('refresh');
        });

        // Confirm delete
        $('#confirmDelete').on('click', function() {
            if (deleteRowId) {
                $.ajax({
                    url: `{{ url('/admin/emplois') }}/${deleteRowId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function() {
                        $('#deleteModal').modal('hide');
                        $('#table_list').bootstrapTable('refresh');
                        loadStats();
                        showToast('Succès', 'Séance supprimée avec succès', 'success');
                    },
                    error: function() {
                        showToast('Erreur', 'Erreur lors de la suppression', 'error');
                    }
                });
            }
        });
    });

    function loadStats() {
        $.ajax({
            url: '{{ route("web.emplois.stats") }}',
            method: 'GET',
            success: function(data) {
                $('#stat-total').text(data.total || 0);
                $('#stat-classes').text(data.classes || 0);
                $('#stat-teachers').text(data.teachers || 0);
                $('#stat-subjects').text(data.subjects || 0);
            },
            error: function() {
                $('#stat-total, #stat-classes, #stat-teachers, #stat-subjects').text('0');
            }
        });
    }

    function loadFiltersData() {
        $.ajax({
            url: '{{ route("web.emplois.filters") }}',
            method: 'GET',
            success: function(data) {
                // Classes
                if (data.classes) {
                    data.classes.forEach(function(item) {
                        $('#filter_class').append(`<option value="${item.id}">${item.nom}</option>`);
                    });
                }
                // Teachers
                if (data.teachers) {
                    data.teachers.forEach(function(item) {
                        $('#filter_teacher').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
                // Jours
                if (data.jours) {
                    data.jours.forEach(function(item) {
                        $('#filter_jour').append(`<option value="${item.id}">${item.libelle_fr}</option>`);
                    });
                }
                // Trimesters
                if (data.trimesters) {
                    data.trimesters.forEach(function(item) {
                        $('#filter_trimester').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
            }
        });
    }

    function showToast(title, message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        }
    }
</script>
@endsection
