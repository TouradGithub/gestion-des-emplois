@extends('layouts.masters.master')

@section('title')
    Pointages - Calendrier
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
<style>
    .fc {
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .fc-event {
        border: 1px solid rgba(17, 153, 142, 0.3) !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 12px !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        background: rgba(17, 153, 142, 0.15) !important;
        color: #333 !important;
    }
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: rgba(17, 153, 142, 0.25) !important;
    }
    .fc-event-title, .fc-event-time {
        color: #333 !important;
    }
    .fc-timegrid-slot {
        height: 50px !important;
    }
    .fc-col-header-cell {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        padding: 10px !important;
    }
    .fc-col-header-cell-cushion {
        color: #fff !important;
        font-weight: 600;
    }
    .fc-toolbar-title {
        font-size: 1.3rem !important;
        font-weight: 600;
        color: #333;
    }
    .fc-button-primary {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        border: none !important;
    }
    .fc-button-primary:hover {
        opacity: 0.9;
    }
    .filter-card {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .filter-card label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    .filter-card .form-control {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    .filter-card .form-control:focus {
        border-color: #11998e;
        box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .empty-state i {
        font-size: 80px;
        color: #11998e;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        color: #333;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #666;
    }
    .modal-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        border-radius: 0;
    }
    .modal-header .close {
        color: #fff;
        opacity: 1;
    }
    .modal-title {
        font-weight: 600;
    }
    .btn-gradient-success-custom {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: opacity 0.3s;
    }
    .btn-gradient-success-custom:hover {
        opacity: 0.9;
        color: #fff;
    }
    .btn-gradient-danger {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: #fff;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
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
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0;
    }
    .page-subtitle {
        color: #666;
        font-size: 0.9rem;
    }
    /* Modal pointage styles */
    .pointage-card {
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    .pointage-info {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .pointage-info i {
        font-size: 24px;
        margin-right: 15px;
        color: #11998e;
    }
    .pointage-info-text h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }
    .pointage-info-text p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    .btn-statut {
        padding: 15px 30px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s;
        border: 3px solid transparent;
    }
    .btn-statut.active {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .btn-present {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: #fff;
    }
    .btn-present:hover, .btn-present.active {
        color: #fff;
        border-color: #0d7377;
    }
    .btn-absent {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: #fff;
    }
    .btn-absent:hover, .btn-absent.active {
        color: #fff;
        border-color: #c0392b;
    }
    .statut-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .statut-badge.present {
        background: rgba(17, 153, 142, 0.15);
        color: #11998e;
    }
    .statut-badge.absent {
        background: rgba(255, 65, 108, 0.15);
        color: #ff416c;
    }
    .statut-badge.pending {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
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
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-success text-white me-2">
                        <i class="mdi mdi-calendar-clock"></i>
                    </span>
                    Pointages - Calendrier
                </h3>
                <p class="page-subtitle mb-0">Gestion des pointages via le calendrier</p>
            </div>
            <a href="{{ route('web.pointages.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <label for="classe_select">
                        <i class="mdi mdi-account-group"></i> Classe <span class="text-danger">*</span>
                    </label>
                    <select id="classe_select" class="form-control" required>
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <label for="trimester_select">
                        <i class="mdi mdi-calendar-range"></i> Trimestre <span class="text-danger">*</span>
                    </label>
                    <select id="trimester_select" class="form-control" disabled>
                        <option value="">-- Sélectionner d'abord une classe --</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="btn_refresh" class="btn btn-gradient-success-custom w-100" disabled>
                    <i class="mdi mdi-refresh"></i> Afficher
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty_state" class="empty-state">
        <i class="mdi mdi-calendar-clock"></i>
        <h4>Aucun emploi du temps à afficher</h4>
        <p>Veuillez sélectionner une classe et un trimestre pour gérer les pointages.</p>
    </div>

    <!-- Calendar Container -->
    <div id="calendar_container" style="display: none;">
        <div class="mb-3">
            <div class="d-flex align-items-center gap-3">
                <span class="statut-badge present"><i class="mdi mdi-check-circle me-1"></i> Présent</span>
                <span class="statut-badge absent"><i class="mdi mdi-close-circle me-1"></i> Absent</span>
                <span class="statut-badge pending"><i class="mdi mdi-clock-outline me-1"></i> Non pointé</span>
            </div>
        </div>
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal Pointage -->
<div class="modal fade" id="pointageModal" tabindex="-1" role="dialog" aria-labelledby="pointageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pointageModalLabel">
                    <i class="mdi mdi-clipboard-check"></i> Enregistrer le pointage
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pointage_emploi_id">
                <input type="hidden" id="pointage_date">
                <input type="hidden" id="pointage_id">

                <!-- Informations de la séance -->
                <div class="pointage-card">
                    <div class="pointage-info">
                        <i class="mdi mdi-book-open-variant"></i>
                        <div class="pointage-info-text">
                            <h5 id="info_matiere">-</h5>
                            <p>Matière</p>
                        </div>
                    </div>
                    <div class="pointage-info">
                        <i class="mdi mdi-account-tie"></i>
                        <div class="pointage-info-text">
                            <h5 id="info_enseignant">-</h5>
                            <p>Enseignant</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pointage-info">
                                <i class="mdi mdi-account-group"></i>
                                <div class="pointage-info-text">
                                    <h5 id="info_classe">-</h5>
                                    <p>Classe</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pointage-info">
                                <i class="mdi mdi-calendar"></i>
                                <div class="pointage-info-text">
                                    <h5 id="info_date">-</h5>
                                    <p>Date</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pointage-info">
                                <i class="mdi mdi-clock-outline"></i>
                                <div class="pointage-info-text">
                                    <h5 id="info_horaire">-</h5>
                                    <p>Horaire</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut actuel -->
                <div class="text-center mb-4" id="statut_actuel_container" style="display: none;">
                    <p class="text-muted mb-2">Statut actuel :</p>
                    <span class="statut-badge present" id="statut_actuel_present" style="display: none;">
                        <i class="mdi mdi-check-circle me-1"></i> Présent
                    </span>
                    <span class="statut-badge absent" id="statut_actuel_absent" style="display: none;">
                        <i class="mdi mdi-close-circle me-1"></i> Absent
                    </span>
                </div>

                <!-- Boutons de choix -->
                <div class="text-center">
                    <p class="text-muted mb-3">Choisir le statut de l'enseignant :</p>
                    <div class="d-flex justify-content-center gap-4">
                        <button type="button" class="btn btn-statut btn-present" id="btn_present">
                            <i class="mdi mdi-check-circle me-2"></i> Présent
                        </button>
                        <button type="button" class="btn btn-statut btn-absent" id="btn_absent">
                            <i class="mdi mdi-close-circle me-2"></i> Absent
                        </button>
                    </div>
                </div>

                <!-- Remarques (optionnel) -->
                <div class="mt-4">
                    <label for="remarques" class="form-label">Remarques (optionnel)</label>
                    <textarea id="remarques" class="form-control" rows="2" placeholder="Ajouter une remarque..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/fr.min.js"></script>
<script>
$(document).ready(function() {
    const SITEURL = "{{ url('/') }}";
    let calendar = null;
    let selectedStatut = null;

    // CSRF Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Événement: Changement de classe
    $('#classe_select').on('change', function() {
        let classId = $(this).val();
        let trimesterSelect = $('#trimester_select');

        trimesterSelect.empty().append('<option value="">-- Chargement... --</option>');
        trimesterSelect.prop('disabled', true);
        $('#btn_refresh').prop('disabled', true);

        if (classId) {
            $.ajax({
                url: SITEURL + '/admin/emplois/get-trimesters',
                type: 'POST',
                data: { class_id: classId },
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

    // Événement: Changement de trimestre
    $('#trimester_select').on('change', function() {
        let trimesterId = $(this).val();
        let classId = $('#classe_select').val();

        if (trimesterId && classId) {
            $('#btn_refresh').prop('disabled', false);
        } else {
            $('#btn_refresh').prop('disabled', true);
        }
    });

    // Événement: Clic sur Afficher
    $('#btn_refresh').on('click', function() {
        loadCalendar();
    });

    // Initialiser et charger le calendrier
    function loadCalendar() {
        let classId = $('#classe_select').val();
        let trimesterId = $('#trimester_select').val();

        if (!classId || !trimesterId) {
            alert('Veuillez sélectionner une classe et un trimestre.');
            return;
        }

        $('#loadingOverlay').show();
        $('#empty_state').hide();
        $('#calendar_container').show();

        if (calendar) {
            calendar.destroy();
        }

        let calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            slotMinTime: '{{ $start_time ?? "08:00:00" }}',
            slotMaxTime: '{{ $end_time ?? "19:00:00" }}',
            slotDuration: '01:00:00',
            allDaySlot: false,
            weekends: true,
            firstDay: 1,
            height: 'auto',
            selectable: false,
            editable: false,
            eventDisplay: 'block',

            // Charger les événements
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: SITEURL + '/admin/pointages/calendar/events',
                    type: 'GET',
                    data: {
                        class_id: classId,
                        trimester_id: trimesterId,
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr
                    },
                    success: function(response) {
                        successCallback(response.events || []);
                        $('#loadingOverlay').hide();
                    },
                    error: function() {
                        failureCallback();
                        $('#loadingOverlay').hide();
                        alert('Erreur lors du chargement des événements.');
                    }
                });
            },

            // Clic sur un événement
            eventClick: function(info) {
                openPointageModal(info.event);
            }
        });

        calendar.render();
    }

    // Ouvrir le modal de pointage
    function openPointageModal(event) {
        selectedStatut = null;
        $('.btn-statut').removeClass('active');
        $('#remarques').val('');

        let emploiId = event.id;
        let eventDate = event.startStr.split('T')[0];

        $('#pointage_emploi_id').val(emploiId);
        $('#pointage_date').val(eventDate);

        // Afficher les infos
        $('#info_matiere').text(event.extendedProps.subject || '-');
        $('#info_enseignant').text(event.extendedProps.teacher || '-');
        $('#info_classe').text(event.extendedProps.classe || '-');
        $('#info_date').text(formatDateFr(eventDate));
        $('#info_horaire').text(event.extendedProps.horaire || '-');

        // Vérifier si un pointage existe déjà
        let statut = event.extendedProps.statut;
        let pointageId = event.extendedProps.pointage_id;

        if (pointageId) {
            $('#pointage_id').val(pointageId);
            $('#statut_actuel_container').show();

            if (statut === 'present') {
                $('#statut_actuel_present').show();
                $('#statut_actuel_absent').hide();
                $('#btn_present').addClass('active');
                selectedStatut = 'present';
            } else {
                $('#statut_actuel_present').hide();
                $('#statut_actuel_absent').show();
                $('#btn_absent').addClass('active');
                selectedStatut = 'absent';
            }
        } else {
            // Par défaut: absent (l'enseignant est considéré absent jusqu'à preuve du contraire)
            $('#pointage_id').val('');
            $('#statut_actuel_container').hide();
            $('#btn_absent').addClass('active');
            selectedStatut = 'absent';
        }

        $('#pointageModal').modal('show');
    }

    function formatDateFr(dateStr) {
        let date = new Date(dateStr);
        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('fr-FR', options);
    }

    // Clic sur Présent
    $('#btn_present').on('click', function() {
        selectedStatut = 'present';
        $('.btn-statut').removeClass('active');
        $(this).addClass('active');
        savePointage();
    });

    // Clic sur Absent
    $('#btn_absent').on('click', function() {
        selectedStatut = 'absent';
        $('.btn-statut').removeClass('active');
        $(this).addClass('active');
        savePointage();
    });

    // Sauvegarder le pointage
    function savePointage() {
        if (!selectedStatut) {
            return;
        }

        let emploiId = $('#pointage_emploi_id').val();
        let datePointage = $('#pointage_date').val();
        let pointageId = $('#pointage_id').val();
        let remarques = $('#remarques').val();

        $('#loadingOverlay').show();

        $.ajax({
            url: SITEURL + '/admin/pointages/calendar/store',
            type: 'POST',
            data: {
                emploi_temps_id: emploiId,
                date_pointage: datePointage,
                statut: selectedStatut,
                remarques: remarques,
                pointage_id: pointageId || null
            },
            success: function(response) {
                $('#loadingOverlay').hide();
                if (response.success) {
                    $('#pointageModal').modal('hide');
                    calendar.refetchEvents();
                    showToast('Succès', response.message, 'success');
                } else {
                    alert(response.message || 'Une erreur est survenue.');
                }
            },
            error: function(xhr) {
                $('#loadingOverlay').hide();
                let message = 'Une erreur est survenue.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    }

    function showToast(title, message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        }
    }
});
</script>
@endsection
