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
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 12px !important;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        background: rgba(220, 220, 220, 0.4) !important;
        color: #000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
    }
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: rgba(200, 200, 200, 0.5) !important;
    }
    .fc-event-title, .fc-event-time {
        color: #000 !important;
    }
    .fc-event .event-content,
    .fc-event .event-matiere,
    .fc-event .event-prof,
    .fc-event .event-salle,
    .fc-event * {
        color: #000 !important;
    }
    .fc-timegrid-slot {
        height: 80px !important;
        border-bottom: none !important;
    }
    /* Remove borders from intermediate hour slots */
    .fc-timegrid-slot-lane {
        border-top: none !important;
    }
    /* Only show border at main time slot starts (8, 10, 12, 15, 17) */
    .fc-timegrid-slot[data-time="08:00:00"],
    .fc-timegrid-slot[data-time="10:00:00"],
    .fc-timegrid-slot[data-time="12:00:00"],
    .fc-timegrid-slot[data-time="14:00:00"],
    .fc-timegrid-slot[data-time="15:00:00"],
    .fc-timegrid-slot[data-time="17:00:00"] {
        border-top: 1px solid #ddd !important;
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
    .event-matiere {
        font-weight: bold;
        font-size: 13px;
    }
    .event-prof {
        font-size: 11px;
        opacity: 0.9;
    }
    .event-salle {
        font-size: 10px;
        opacity: 0.8;
    }
    .event-type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .event-type-td { background-color: #28a745; }
    .event-type-tp { background-color: #007bff; }
    .event-type-project { background-color: #fd7e14; }

    /* Pointage Buttons on Events */
    .event-pointage-btns {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 5px;
    }
    .btn-evt-pointage {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid;
        transition: all 0.2s;
    }
    .btn-evt-present {
        background: #fff;
        color: #28a745;
        border-color: #28a745;
    }
    .btn-evt-present:hover, .btn-evt-present.active {
        background: #28a745;
        color: #fff;
    }
    .btn-evt-absent {
        background: #fff;
        color: #dc3545;
        border-color: #dc3545;
    }
    .btn-evt-absent:hover, .btn-evt-absent.active {
        background: #dc3545;
        color: #fff;
    }
    .fc-event.pointage-present {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border-color: #28a745 !important;
    }
    .fc-event.pointage-present * {
        color: #fff !important;
    }
    .fc-event.pointage-present .btn-evt-present {
        background: #fff !important;
        color: #28a745 !important;
    }
    .fc-event.pointage-present .btn-evt-absent {
        background: transparent !important;
        color: #fff !important;
        border-color: #fff !important;
    }
    .fc-event.pointage-absent {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        border-color: #dc3545 !important;
    }
    .fc-event.pointage-absent * {
        color: #fff !important;
    }
    .fc-event.pointage-absent .btn-evt-absent {
        background: #fff !important;
        color: #dc3545 !important;
    }
    .fc-event.pointage-absent .btn-evt-present {
        background: transparent !important;
        color: #fff !important;
        border-color: #fff !important;
    }

    .info-type-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
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
            slotLabelInterval: '01:00:00',
            slotLabelFormat: function(date) {
                let hour = date.date.hour;
                // Display 2-hour block labels: 8-10, 10-12, 12-14, 15-17, 17-19 (skip 14-15 break)
                if (hour === 8) return '8h-10h';
                if (hour === 10) return '10h-12h';
                if (hour === 12) return '12h-14h';
                if (hour === 15) return '15h-17h';
                if (hour === 17) return '17h-19h';
                // Hide labels for intermediate hours (9, 11, 13, 14, 16, 18)
                return '';
            },
            allDaySlot: false,
            weekends: true,
            firstDay: 1,
            height: 'auto',
            selectable: false,
            editable: false,
            eventDisplay: 'block',
            displayEventTime: false,

            // Personnaliser l'affichage des événements
            eventContent: function(arg) {
                let event = arg.event;
                let matiere = event.extendedProps.matiere || event.extendedProps.subject || event.title || '';
                let prof = event.extendedProps.prof || event.extendedProps.teacher || '';
                let salle = event.extendedProps.salle || '';
                let subjectType = event.extendedProps.subject_type || null;
                let statut = event.extendedProps.statut || null;
                let eventDate = event.start ? event.start.toISOString().split('T')[0] : '';

                let html = '<div class="event-content">';

                // Afficher la matière avec le type entre parenthèses
                let matiereWithType = matiere;
                if (subjectType && subjectType.name) {
                    matiereWithType += ' (' + subjectType.name + ')';
                }

                html += '<div class="event-matiere">' + matiereWithType + '</div>';
                if (salle) {
                    html += '<div class="event-salle">' + salle + '</div>';
                }
                if (prof) {
                    html += '<div class="event-prof">' + prof + '</div>';
                }

                // Boutons de pointage
                let presentActive = statut === 'present' ? 'active' : '';
                let absentActive = statut === 'absent' ? 'active' : '';
                html += '<div class="event-pointage-btns">';
                html += '<button class="btn-evt-pointage btn-evt-present ' + presentActive + '" data-emploi-id="' + event.id + '" data-date="' + eventDate + '" data-statut="present" onclick="savePointage(this, event)">P</button>';
                html += '<button class="btn-evt-pointage btn-evt-absent ' + absentActive + '" data-emploi-id="' + event.id + '" data-date="' + eventDate + '" data-statut="absent" onclick="savePointage(this, event)">A</button>';
                html += '</div>';

                html += '</div>';

                return { html: html };
            },

            // Appliquer les classes CSS selon le statut
            eventClassNames: function(arg) {
                let statut = arg.event.extendedProps.statut;
                if (statut === 'present') {
                    return ['pointage-present'];
                } else if (statut === 'absent') {
                    return ['pointage-absent'];
                }
                return [];
            },

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

            // Désactiver le clic sur événement (on utilise les boutons)
            eventClick: function(info) {
                // Ne rien faire, on utilise les boutons P et A
            }
        });

        calendar.render();
    }

    // Fonction globale pour sauvegarder le pointage
    window.savePointage = function(btn, e) {
        e.stopPropagation();
        e.preventDefault();

        let $btn = $(btn);
        let emploiId = $btn.data('emploi-id');
        let datePointage = $btn.data('date');
        let statut = $btn.data('statut');
        let $container = $btn.closest('.event-pointage-btns');

        // Disable buttons
        $container.find('.btn-evt-pointage').prop('disabled', true);

        // Add loading
        let originalText = $btn.text();
        $btn.text('...');

        $.ajax({
            url: SITEURL + '/admin/pointages/calendar/store',
            type: 'POST',
            data: {
                emploi_temps_id: emploiId,
                date_pointage: datePointage,
                statut: statut
            },
            success: function(response) {
                $container.find('.btn-evt-pointage').prop('disabled', false);
                $btn.text(originalText);

                if (response.success) {
                    // Refresh calendar to update colors
                    if (calendar) {
                        calendar.refetchEvents();
                    }
                } else {
                    alert(response.message || 'Erreur');
                }
            },
            error: function() {
                $container.find('.btn-evt-pointage').prop('disabled', false);
                $btn.text(originalText);
                alert('Erreur lors de l\'enregistrement');
            }
        });
    };

    function showToast(title, message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        }
    }
});
</script>
@endsection
