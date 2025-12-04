@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.nouveau_pointage') }}
@endsection

@section('css')
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
    .info-card {
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
    }
    .info-card .card-body {
        padding: 25px;
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
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: block;
    }
    .form-group label .text-danger {
        color: #666 !important;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px 15px;
        transition: all 0.3s;
        font-size: 0.95rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .form-control:disabled, .form-select:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    .statut-selection {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .statut-option {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.3s;
        background: #fff;
        margin-bottom: 10px;
    }
    .statut-option:hover {
        border-color: #1a1a1a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .statut-option.selected-present {
        border-color: #1a1a1a;
        background: #f5f5f5;
    }
    .statut-option.selected-absent {
        border-color: #666;
        background: #fafafa;
    }
    .statut-option input[type="radio"] {
        display: none;
    }
    .statut-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 15px;
    }
    .statut-icon.present {
        background: #1a1a1a;
        color: #fff;
    }
    .statut-icon.absent {
        background: #fff;
        color: #1a1a1a;
        border: 2px solid #1a1a1a;
    }
    .statut-text h6 {
        margin: 0;
        font-weight: 700;
        font-size: 1rem;
        color: #1a1a1a;
    }
    .statut-text p {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }
    .time-inputs {
        display: flex;
        gap: 15px;
    }
    .time-inputs .form-group {
        flex: 1;
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
    .info-alert {
        background: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        color: #1a1a1a;
    }
    .info-alert i {
        font-size: 1.3rem;
        margin-right: 10px;
    }
    .info-alert ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }
    .info-alert li {
        margin-bottom: 5px;
        color: #333;
    }
    .cours-info-card {
        background: #fafafa;
        border: 2px solid #1a1a1a;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    .cours-info-card h6 {
        color: #1a1a1a;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .cours-info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .cours-info-item:last-child {
        border-bottom: none;
    }
    .cours-info-item .label {
        color: #666;
        font-weight: 500;
    }
    .cours-info-item .value {
        color: #1a1a1a;
        font-weight: 600;
    }
    .actions-row {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
        margin-top: 25px;
    }
    .is-invalid {
        border-color: #666 !important;
    }
    .invalid-feedback {
        color: #666;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #e0e0e0;
        border-top: 3px solid #1a1a1a;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 10px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .quick-info-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-right: 10px;
        margin-bottom: 10px;
        background: #f5f5f5;
        color: #1a1a1a;
        border: 1px solid #e0e0e0;
    }
    .btn-outline-success, .btn-outline-primary {
        border: 2px solid #1a1a1a;
        color: #1a1a1a;
        background: #fff;
    }
    .btn-outline-success:hover, .btn-outline-primary:hover {
        background: #1a1a1a;
        color: #fff;
        border-color: #1a1a1a;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3><i class="mdi mdi-clipboard-plus me-2"></i> Nouveau Pointage</h3>
                <p>Enregistrer la présence ou l'absence d'un enseignant</p>
            </div>
            <a href="{{ route('web.pointages.index') }}" class="btn btn-light">
                <i class="mdi mdi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card main-card">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="mdi mdi-alert-circle me-2"></i>Erreurs détectées:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('web.pointages.store') }}" id="pointageForm">
                        @csrf

                        <!-- Section: Informations de base -->
                        <div class="section-title">
                            <i class="mdi mdi-calendar-account"></i> Informations de base
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="mdi mdi-calendar me-1"></i> Date de pointage <span class="text-danger">*</span></label>
                                    <input type="date"
                                           class="form-control @error('date_pointage') is-invalid @enderror"
                                           id="date_pointage"
                                           name="date_pointage"
                                           value="{{ old('date_pointage', $today) }}"
                                           required>
                                    @error('date_pointage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="mdi mdi-account-tie me-1"></i> Enseignant <span class="text-danger">*</span></label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id"
                                            required>
                                        <option value="">-- Sélectionner un enseignant --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Cours -->
                        <div class="section-title">
                            <i class="mdi mdi-book-clock"></i> Sélection du cours
                        </div>

                        <div class="form-group">
                            <label><i class="mdi mdi-school me-1"></i> Cours programmé <span class="text-danger">*</span></label>
                            <select class="form-select @error('emploi_temps_id') is-invalid @enderror"
                                    id="emploi_temps_id"
                                    name="emploi_temps_id"
                                    required
                                    disabled>
                                <option value="">-- Sélectionner d'abord un enseignant et une date --</option>
                            </select>
                            @error('emploi_temps_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section: Statut -->
                        <div class="section-title">
                            <i class="mdi mdi-account-check"></i> Statut de présence
                        </div>

                        <div class="statut-selection">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="statut-option" id="option-present">
                                        <input type="radio" name="statut" value="present" {{ old('statut') == 'present' ? 'checked' : '' }}>
                                        <div class="statut-icon present">
                                            <i class="mdi mdi-check"></i>
                                        </div>
                                        <div class="statut-text">
                                            <h6>Présent</h6>
                                            <p>L'enseignant était présent</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="statut-option" id="option-absent">
                                        <input type="radio" name="statut" value="absent" {{ old('statut') == 'absent' ? 'checked' : '' }}>
                                        <div class="statut-icon absent">
                                            <i class="mdi mdi-close"></i>
                                        </div>
                                        <div class="statut-text">
                                            <h6>Absent</h6>
                                            <p>L'enseignant était absent</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('statut')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section: Heures (visible uniquement si présent) -->
                        <div id="heures-section" style="display: none;">
                            <div class="section-title">
                                <i class="mdi mdi-clock-outline"></i> Horaires de présence
                            </div>

                            <div class="time-inputs">
                                <div class="form-group">
                                    <label><i class="mdi mdi-clock-in me-1"></i> Heure d'arrivée</label>
                                    <input type="time"
                                           class="form-control @error('heure_arrivee') is-invalid @enderror"
                                           id="heure_arrivee"
                                           name="heure_arrivee"
                                           value="{{ old('heure_arrivee') }}">
                                    @error('heure_arrivee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label><i class="mdi mdi-clock-out me-1"></i> Heure de départ</label>
                                    <input type="time"
                                           class="form-control @error('heure_depart') is-invalid @enderror"
                                           id="heure_depart"
                                           name="heure_depart"
                                           value="{{ old('heure_depart') }}">
                                    @error('heure_depart')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Remarques -->
                        <div class="section-title">
                            <i class="mdi mdi-comment-text"></i> Remarques
                        </div>

                        <div class="form-group">
                            <label><i class="mdi mdi-note-text me-1"></i> Notes additionnelles</label>
                            <textarea class="form-control @error('remarques') is-invalid @enderror"
                                      id="remarques"
                                      name="remarques"
                                      rows="3"
                                      placeholder="Ajoutez des remarques ou observations (optionnel)...">{{ old('remarques') }}</textarea>
                            @error('remarques')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="actions-row">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <a href="{{ route('web.pointages.index') }}" class="btn btn-back">
                                    <i class="mdi mdi-close me-2"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-submit" id="submitBtn">
                                    <i class="mdi mdi-content-save me-2"></i> Enregistrer le pointage
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="mdi mdi-information me-2"></i> Instructions
                    </h5>

                    <div class="info-alert">
                        <i class="mdi mdi-lightbulb-on"></i>
                        <strong>Comment procéder :</strong>
                        <ul>
                            <li>Sélectionnez la date du pointage</li>
                            <li>Choisissez l'enseignant concerné</li>
                            <li>Les cours programmés s'afficheront automatiquement</li>
                            <li>Marquez le statut de présence</li>
                            <li>Pour un présent, vous pouvez ajouter les heures</li>
                        </ul>
                    </div>

                    <!-- Cours Info (affiché dynamiquement) -->
                    <div id="cours-info" class="cours-info-card" style="display: none;">
                        <h6><i class="mdi mdi-book-open-variant me-2"></i> Détails du cours</h6>
                        <div class="cours-info-item">
                            <span class="label">Classe</span>
                            <span class="value" id="info-classe">-</span>
                        </div>
                        <div class="cours-info-item">
                            <span class="label">Matière</span>
                            <span class="value" id="info-matiere">-</span>
                        </div>
                        <div class="cours-info-item">
                            <span class="label">Jour</span>
                            <span class="value" id="info-jour">-</span>
                        </div>
                        <div class="cours-info-item">
                            <span class="label">Horaires</span>
                            <span class="value" id="info-horaires">-</span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="mdi mdi-chart-bar me-2"></i> Raccourcis</h6>
                        <a href="{{ route('web.pointages.rapide') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
                            <i class="mdi mdi-clock-fast me-1"></i> Pointage rapide
                        </a>
                        <a href="{{ route('web.pointages.calendar') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="mdi mdi-calendar me-1"></i> Voir le calendrier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherSelect = document.getElementById('teacher_id');
    const dateInput = document.getElementById('date_pointage');
    const emploiSelect = document.getElementById('emploi_temps_id');
    const coursInfo = document.getElementById('cours-info');
    const heuresSection = document.getElementById('heures-section');
    const optionPresent = document.getElementById('option-present');
    const optionAbsent = document.getElementById('option-absent');
    const heureArrivee = document.getElementById('heure_arrivee');
    const heureDepart = document.getElementById('heure_depart');

    // Charger les cours pour l'enseignant et la date
    function loadEmploisForTeacher() {
        const teacherId = teacherSelect.value;
        const date = dateInput.value;

        if (!teacherId || !date) {
            emploiSelect.disabled = true;
            emploiSelect.innerHTML = '<option value="">-- Sélectionner d\'abord un enseignant et une date --</option>';
            coursInfo.style.display = 'none';
            return;
        }

        // Afficher un indicateur de chargement
        emploiSelect.innerHTML = '<option value=""><span class="loading-spinner"></span> Chargement des cours...</option>';

        fetch(`{{ route('web.pointages.get-emplois') }}?teacher_id=${teacherId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                emploiSelect.innerHTML = '<option value="">-- Sélectionner un cours --</option>';

                if (data.emplois && data.emplois.length > 0) {
                    data.emplois.forEach(emploi => {
                        const option = document.createElement('option');
                        option.value = emploi.id;
                        option.textContent = emploi.display;
                        option.dataset.classe = emploi.classe_nom;
                        option.dataset.matiere = emploi.subject_nom;
                        option.dataset.horaires = emploi.horaires;
                        option.dataset.jour = emploi.jour_nom;
                        emploiSelect.appendChild(option);
                    });
                    emploiSelect.disabled = false;
                } else {
                    emploiSelect.innerHTML = '<option value="">-- Aucun cours trouvé pour ce jour --</option>';
                    emploiSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                emploiSelect.innerHTML = '<option value="">-- Erreur de chargement --</option>';
                emploiSelect.disabled = true;
            });
    }

    // Mettre à jour les infos du cours
    function updateCoursInfo() {
        const selectedOption = emploiSelect.options[emploiSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
            document.getElementById('info-classe').textContent = selectedOption.dataset.classe || '-';
            document.getElementById('info-matiere').textContent = selectedOption.dataset.matiere || '-';
            document.getElementById('info-horaires').textContent = selectedOption.dataset.horaires || '-';
            document.getElementById('info-jour').textContent = selectedOption.dataset.jour || '-';
            coursInfo.style.display = 'block';
        } else {
            coursInfo.style.display = 'none';
        }
    }

    // Gestion du statut de présence
    function handleStatutChange() {
        const presentRadio = document.querySelector('input[name="statut"][value="present"]');
        const absentRadio = document.querySelector('input[name="statut"][value="absent"]');

        // Reset styles
        optionPresent.classList.remove('selected-present', 'selected-absent');
        optionAbsent.classList.remove('selected-present', 'selected-absent');

        if (presentRadio.checked) {
            optionPresent.classList.add('selected-present');
            heuresSection.style.display = 'block';
            // Auto-remplir l'heure d'arrivée
            if (!heureArrivee.value) {
                const now = new Date();
                heureArrivee.value = now.getHours().toString().padStart(2, '0') + ':' +
                                    now.getMinutes().toString().padStart(2, '0');
            }
        } else if (absentRadio.checked) {
            optionAbsent.classList.add('selected-absent');
            heuresSection.style.display = 'none';
            heureArrivee.value = '';
            heureDepart.value = '';
        }
    }

    // Event listeners
    teacherSelect.addEventListener('change', loadEmploisForTeacher);
    dateInput.addEventListener('change', loadEmploisForTeacher);
    emploiSelect.addEventListener('change', updateCoursInfo);

    // Statut radio buttons
    document.querySelectorAll('input[name="statut"]').forEach(radio => {
        radio.addEventListener('change', handleStatutChange);
    });

    // Click sur les options de statut
    optionPresent.addEventListener('click', function() {
        document.querySelector('input[name="statut"][value="present"]').checked = true;
        handleStatutChange();
    });

    optionAbsent.addEventListener('click', function() {
        document.querySelector('input[name="statut"][value="absent"]').checked = true;
        handleStatutChange();
    });

    // Initialisation
    if (teacherSelect.value && dateInput.value) {
        loadEmploisForTeacher();
    }

    // Initialiser le statut si déjà sélectionné
    handleStatutChange();
});
</script>
@endsection
