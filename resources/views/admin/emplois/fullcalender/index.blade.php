@extends('layouts.masters.master')

@section('title')
    {{ __('Emploi du Temps - Calendrier') }}
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
    .fc-event-title {
        font-weight: 600;
        color: #000 !important;
    }
    .fc-event-time {
        font-size: 11px;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: none !important;
    }
    .fc-button-primary:hover {
        opacity: 0.9;
    }
    .filter-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        color: #667eea;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: opacity 0.3s;
    }
    .btn-gradient-primary:hover {
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
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
                <h3 class="page-title">Emploi du Temps</h3>
                <p class="page-subtitle mb-0">Gestion du calendrier des cours</p>
            </div>
            <a href="{{ route('web.emplois.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <label for="classe_select">
                        <i class="fas fa-users"></i> Classe <span class="text-danger">*</span>
                    </label>
                    <select id="classe_select" class="form-control" required>
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach($classes_of_this_year as $class)
                            <option value="{{ $class->id }}">{{ $class->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group mb-0">
                    <label for="trimester_select">
                        <i class="fas fa-calendar-alt"></i> Trimestre <span class="text-danger">*</span>
                    </label>
                    <select id="trimester_select" class="form-control" disabled>
                        <option value="">-- Sélectionner d'abord une classe --</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="btn_refresh" class="btn btn-gradient-primary w-100" disabled>
                    <i class="fas fa-sync-alt"></i> Afficher
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty_state" class="empty-state">
        <i class="fas fa-calendar-alt"></i>
        <h4>Aucun emploi du temps à afficher</h4>
        <p>Veuillez sélectionner une classe et un trimestre pour visualiser l'emploi du temps.</p>
    </div>

    <!-- Calendar Container -->
    <div id="calendar_container" style="display: none;">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal Créer/Modifier Séance -->
<div class="modal fade" id="seanceModal" tabindex="-1" role="dialog" aria-labelledby="seanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seanceModalLabel">
                    <i class="fas fa-plus-circle"></i> Nouvelle Séance
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="seanceForm">
                    <input type="hidden" id="seance_id" name="seance_id">
                    <input type="hidden" id="form_class_id" name="class_id">
                    <input type="hidden" id="form_trimester_id" name="trimester_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jour_id">
                                    <i class="fas fa-calendar-day"></i> Jour <span class="text-danger">*</span>
                                </label>
                                <select id="jour_id" name="jour_id" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horaire_ids">
                                    <i class="fas fa-clock"></i> Horaires <span class="text-danger">*</span>
                                </label>
                                <select id="horaire_ids" name="horaire_ids[]" class="form-control" multiple required>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teacher_id">
                                    <i class="fas fa-chalkboard-teacher"></i> Enseignant <span class="text-danger">*</span>
                                </label>
                                <select id="teacher_id" name="teacher_id" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject_id">
                                    <i class="fas fa-book"></i> Matière <span class="text-danger">*</span>
                                </label>
                                <select id="subject_id" name="subject_id" class="form-control" required>
                                    <option value="">-- Sélectionner un enseignant d'abord --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salle_id">
                                    <i class="fas fa-door-open"></i> Salle
                                </label>
                                <select id="salle_id" name="salle_de_classe_id" class="form-control">
                                    <option value="">-- Optionnel --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" id="btn_delete_seance" class="btn btn-gradient-danger" style="display: none;">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
                <button type="button" id="btn_save_seance" class="btn btn-gradient-primary">
                    <i class="fas fa-save"></i> Enregistrer
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
    let jours = [];
    let horaires = [];
    let salles = [];
    let teachers = [];

    // CSRF Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Charger les données de référence
    loadReferenceData();

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
            loadTeachers(classId, trimesterId);
        } else {
            $('#btn_refresh').prop('disabled', true);
        }
    });

    // Événement: Clic sur Afficher
    $('#btn_refresh').on('click', function() {
        loadCalendar();
    });

    // Événement: Changement d'enseignant dans le modal
    $('#teacher_id').on('change', function() {
        let teacherId = $(this).val();
        let classId = $('#classe_select').val();
        let trimesterId = $('#trimester_select').val();

        loadSubjectsForTeacher(teacherId, classId, trimesterId);
    });

    // Charger les données de référence (jours, horaires, salles)
    function loadReferenceData() {
        $.ajax({
            url: SITEURL + '/admin/emplois/calendar/reference-data',
            type: 'GET',
            success: function(response) {
                jours = response.jours || [];
                horaires = response.horaires || [];
                salles = response.salles || [];

                // Remplir les sélecteurs
                populateJoursSelect();
                populateHorairesSelect();
                populateSallesSelect();
            },
            error: function() {
                console.error('Erreur lors du chargement des données de référence');
            }
        });
    }

    function populateJoursSelect() {
        let select = $('#jour_id');
        select.empty().append('<option value="">-- Sélectionner --</option>');
        $.each(jours, function(i, jour) {
            select.append(`<option value="${jour.id}">${jour.libelle_fr}</option>`);
        });
    }

    function populateHorairesSelect() {
        let select = $('#horaire_ids');
        select.empty();
        $.each(horaires, function(i, horaire) {
            select.append(`<option value="${horaire.id}">${horaire.libelle_fr}</option>`);
        });
    }

    function populateSallesSelect() {
        let select = $('#salle_id');
        select.empty().append('<option value="">-- Optionnel --</option>');
        $.each(salles, function(i, salle) {
            select.append(`<option value="${salle.id}">${salle.name}</option>`);
        });
    }

    function loadTeachers(classId, trimesterId) {
        $.ajax({
            url: SITEURL + '/admin/emplois/get-teachers',
            type: 'GET',
            data: { class_id: classId, trimester_id: trimesterId },
            success: function(response) {
                teachers = response.data || [];
                populateTeachersSelect();
            },
            error: function() {
                console.error('Erreur lors du chargement des enseignants');
            }
        });
    }

    function populateTeachersSelect() {
        let select = $('#teacher_id');
        select.empty().append('<option value="">-- Sélectionner --</option>');
        $.each(teachers, function(i, teacher) {
            select.append(`<option value="${teacher.id}">${teacher.full_name}</option>`);
        });
    }

    function loadSubjectsForTeacher(teacherId, classId, trimesterId) {
        let select = $('#subject_id');
        select.empty().append('<option value="">-- Chargement... --</option>');

        if (!teacherId) {
            select.empty().append('<option value="">-- Sélectionner un enseignant d\'abord --</option>');
            return;
        }

        $.ajax({
            url: SITEURL + '/admin/emplois/get-subjects',
            type: 'GET',
            data: { teacher_id: teacherId, class_id: classId, trimester_id: trimesterId },
            success: function(response) {
                select.empty().append('<option value="">-- Sélectionner --</option>');
                if (response.subjects && response.subjects.length > 0) {
                    $.each(response.subjects, function(i, subject) {
                        select.append(`<option value="${subject.id}">${subject.name}</option>`);
                    });
                } else {
                    select.empty().append('<option value="">-- Aucune matière disponible --</option>');
                }
            },
            error: function() {
                select.empty().append('<option value="">-- Erreur de chargement --</option>');
            }
        });
    }

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
            slotMinTime: '{{ $start_time_calandrie ?? "08:00:00" }}',
            slotMaxTime: '{{ $end_time_calandrie ?? "19:00:00" }}',
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
            selectable: true,
            editable: false,
            eventDisplay: 'block',
            displayEventTime: false,

            // Personnaliser l'affichage des événements
            eventContent: function(arg) {
                let event = arg.event;
                let matiere = event.extendedProps.matiere || event.title || '';
                let prof = event.extendedProps.prof || event.extendedProps.teacher || '';
                let salle = event.extendedProps.salle || '';

                let html = '<div class="event-content">';
                html += '<div class="event-matiere">' + matiere + '</div>';
                if (prof) {
                    html += '<div class="event-prof">' + prof + '</div>';
                }
                if (salle) {
                    html += '<div class="event-salle">' + salle + '</div>';
                }
                html += '</div>';

                return { html: html };
            },

            // Charger les événements
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: SITEURL + '/admin/emplois/calendar/events',
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

            // Clic sur un événement existant (modifier)
            eventClick: function(info) {
                openEditModal(info.event);
            },

            // Sélection d'une plage horaire (créer)
            select: function(selectionInfo) {
                openCreateModal(selectionInfo);
            }
        });

        calendar.render();
    }

    // Ouvrir le modal de création
    function openCreateModal(selectionInfo) {
        resetModal();

        let classId = $('#classe_select').val();
        let trimesterId = $('#trimester_select').val();

        $('#seanceModalLabel').html('<i class="fas fa-plus-circle"></i> Nouvelle Séance');
        $('#btn_delete_seance').hide();

        $('#form_class_id').val(classId);
        $('#form_trimester_id').val(trimesterId);

        // Déterminer le jour à partir de la sélection
        let dayOfWeek = selectionInfo.start.getDay();
        let jourId = getJourIdFromDayOfWeek(dayOfWeek);
        if (jourId) {
            $('#jour_id').val(jourId);
        }

        // Déterminer l'horaire à partir de la sélection
        let startTime = formatTime(selectionInfo.start);
        let horaireId = getHoraireIdFromTime(startTime);
        if (horaireId) {
            $('#horaire_ids').val([horaireId]);
        }

        $('#seanceModal').modal('show');
    }

    // Ouvrir le modal de modification
    function openEditModal(event) {
        resetModal();

        let classId = $('#classe_select').val();
        let trimesterId = $('#trimester_select').val();

        $('#seanceModalLabel').html('<i class="fas fa-edit"></i> Modifier la Séance');
        $('#btn_delete_seance').show();

        $('#seance_id').val(event.id);
        $('#form_class_id').val(classId);
        $('#form_trimester_id').val(trimesterId);

        // Charger les détails de la séance
        $.ajax({
            url: SITEURL + '/admin/emplois/calendar/event/' + event.id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let seance = response.seance;

                    $('#jour_id').val(seance.jour_id);
                    $('#horaire_ids').val(seance.horaire_ids);
                    $('#teacher_id').val(seance.teacher_id);
                    $('#salle_id').val(seance.salle_de_classe_id);

                    // Charger les matières puis sélectionner
                    loadSubjectsForTeacher(seance.teacher_id, classId, trimesterId);
                    setTimeout(function() {
                        $('#subject_id').val(seance.subject_id);
                    }, 500);
                }
            }
        });

        $('#seanceModal').modal('show');
    }

    function resetModal() {
        $('#seanceForm')[0].reset();
        $('#seance_id').val('');
        $('#subject_id').empty().append('<option value="">-- Sélectionner un enseignant d\'abord --</option>');
    }

    function getJourIdFromDayOfWeek(dayOfWeek) {
        // 0 = Dimanche, 1 = Lundi, etc.
        // Mapper vers les IDs des jours dans la base de données
        let dayMapping = {
            1: 1, // Lundi
            2: 2, // Mardi
            3: 3, // Mercredi
            4: 4, // Jeudi
            5: 5, // Vendredi
            6: 6, // Samedi
            0: 7  // Dimanche
        };

        // Chercher le jour correspondant
        for (let jour of jours) {
            if (jour.ordre == dayMapping[dayOfWeek]) {
                return jour.id;
            }
        }
        return null;
    }

    function getHoraireIdFromTime(time) {
        for (let horaire of horaires) {
            if (horaire.start_time && horaire.start_time.substring(0, 5) == time) {
                return horaire.id;
            }
        }
        return null;
    }

    function formatTime(date) {
        let hours = date.getHours().toString().padStart(2, '0');
        let minutes = date.getMinutes().toString().padStart(2, '0');
        return hours + ':' + minutes;
    }

    // Sauvegarder la séance
    $('#btn_save_seance').on('click', function() {
        let seanceId = $('#seance_id').val();
        let url = seanceId
            ? SITEURL + '/admin/emplois/calendar/event/' + seanceId
            : SITEURL + '/admin/emplois/calendar/event';
        let method = seanceId ? 'PUT' : 'POST';

        let data = {
            class_id: $('#form_class_id').val(),
            trimester_id: $('#form_trimester_id').val(),
            jour_id: $('#jour_id').val(),
            horaire_ids: $('#horaire_ids').val(),
            teacher_id: $('#teacher_id').val(),
            subject_id: $('#subject_id').val(),
            salle_de_classe_id: $('#salle_id').val()
        };

        // Validation
        if (!data.jour_id || !data.horaire_ids || data.horaire_ids.length == 0 ||
            !data.teacher_id || !data.subject_id) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }

        $('#loadingOverlay').show();

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(response) {
                $('#loadingOverlay').hide();
                if (response.success) {
                    $('#seanceModal').modal('hide');
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
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });

    // Supprimer la séance
    $('#btn_delete_seance').on('click', function() {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette séance ?')) {
            return;
        }

        let seanceId = $('#seance_id').val();

        $('#loadingOverlay').show();

        $.ajax({
            url: SITEURL + '/admin/emplois/calendar/event/' + seanceId,
            type: 'DELETE',
            success: function(response) {
                $('#loadingOverlay').hide();
                if (response.success) {
                    $('#seanceModal').modal('hide');
                    calendar.refetchEvents();
                    showToast('Succès', response.message, 'success');
                } else {
                    alert(response.message || 'Une erreur est survenue.');
                }
            },
            error: function() {
                $('#loadingOverlay').hide();
                alert('Une erreur est survenue lors de la suppression.');
            }
        });
    });

    function showToast(title, message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        }
    }
});
</script>
@endsection
