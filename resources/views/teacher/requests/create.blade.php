@extends('layouts.teacher.master')

@section('title', 'Nouvelle demande de séance')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="mdi mdi-plus-circle me-2"></i>
                    Demande d'ajout d'une nouvelle séance
                </h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('teacher.requests.store') }}" method="POST" id="requestForm">
                    @csrf

                    <div class="row">
                        <!-- 1. Classe (Premier choix) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Classe <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">-- Choisir la classe --</option>
                                @php
                                    $classes = $subjectTeachers->pluck('classe')->unique('id');
                                @endphp
                                @foreach($classes as $classe)
                                    @if($classe)
                                        <option value="{{ $classe->id }}" {{ old('class_id') == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. Matière (après le choix de la classe) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matière <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required disabled>
                                <option value="">-- Choisir d'abord la classe --</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 3. Trimestre (après le choix de la classe) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trimestre <span class="text-danger">*</span></label>
                            <select name="trimester_id" id="trimester_id" class="form-select @error('trimester_id') is-invalid @enderror" required disabled>
                                <option value="">-- Choisir d'abord la classe --</option>
                            </select>
                            @error('trimester_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 4. Jour (après le choix du trimestre) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jour <span class="text-danger">*</span></label>
                            <select name="jour_id" id="jour_id" class="form-select @error('jour_id') is-invalid @enderror" required disabled>
                                <option value="">-- Choisir d'abord le trimestre --</option>
                            </select>
                            @error('jour_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 5. Horaire (après le choix du jour) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horaire <span class="text-danger">*</span></label>
                            <select name="horaire_id" id="horaire_id" class="form-select @error('horaire_id') is-invalid @enderror" required disabled>
                                <option value="">-- Choisir d'abord le jour --</option>
                            </select>
                            <small class="text-muted" id="horaire_hint"></small>
                            @error('horaire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 6. Salle (après le choix de l'horaire) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salle <span class="text-danger">*</span></label>
                            <select name="salle_de_classe_id" id="salle_de_classe_id" class="form-select @error('salle_de_classe_id') is-invalid @enderror" required disabled>
                                <option value="">-- Choisir d'abord l'horaire --</option>
                            </select>
                            <small class="text-muted" id="salle_hint"></small>
                            @error('salle_de_classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Note (optionnel)</label>
                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3" placeholder="Ajouter une note pour l'administration...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('teacher.requests.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            Retour
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="mdi mdi-send me-1"></i>
                            Envoyer la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Données des jours
    var joursData = @json($jours);

    // 1. Lors de la sélection de la classe
    $('#class_id').on('change', function() {
        var classId = $(this).val();
        var subjectSelect = $('#subject_id');
        var trimesterSelect = $('#trimester_id');

        if (classId) {
            // Récupérer les matières pour cette classe
            $.ajax({
                url: '{{ route("teacher.requests.get-subjects") }}',
                type: 'GET',
                data: { class_id: classId },
                success: function(response) {
                    subjectSelect.empty().append('<option value="">-- Choisir la matière --</option>');
                    $.each(response.subjects, function(index, subject) {
                        subjectSelect.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                    });
                    subjectSelect.prop('disabled', false);
                },
                error: function() {
                    Swal.fire('Erreur!', 'Échec de récupération des matières', 'error');
                }
            });

            // Récupérer les trimestres pour cette classe
            $.ajax({
                url: '{{ route("teacher.requests.get-trimesters") }}',
                type: 'GET',
                data: { class_id: classId },
                success: function(response) {
                    trimesterSelect.empty().append('<option value="">-- Choisir le trimestre --</option>');
                    $.each(response.trimesters, function(index, trimester) {
                        trimesterSelect.append('<option value="' + trimester.id + '">' + trimester.name + '</option>');
                    });
                    trimesterSelect.prop('disabled', false);
                },
                error: function() {
                    Swal.fire('Erreur!', 'Échec de récupération des trimestres', 'error');
                }
            });
        } else {
            resetField('#subject_id', '-- Choisir d\'abord la classe --');
            resetField('#trimester_id', '-- Choisir d\'abord la classe --');
        }

        // Réinitialiser les champs suivants
        resetField('#jour_id', '-- Choisir d\'abord le trimestre --');
        resetField('#horaire_id', '-- Choisir d\'abord le jour --');
        resetField('#salle_de_classe_id', '-- Choisir d\'abord l\'horaire --');
        $('#horaire_hint').text('');
        $('#salle_hint').text('');
    });

    // 2. Lors de la sélection du trimestre, activer les jours
    $('#trimester_id').on('change', function() {
        var trimesterId = $(this).val();
        var jourSelect = $('#jour_id');

        if (trimesterId) {
            jourSelect.empty().append('<option value="">-- Choisir le jour --</option>');
            $.each(joursData, function(index, jour) {
                jourSelect.append('<option value="' + jour.id + '">' + jour.libelle_fr + '</option>');
            });
            jourSelect.prop('disabled', false);
        } else {
            resetField('#jour_id', '-- Choisir d\'abord le trimestre --');
        }

        resetField('#horaire_id', '-- Choisir d\'abord le jour --');
        resetField('#salle_de_classe_id', '-- Choisir d\'abord l\'horaire --');
        $('#horaire_hint').text('');
        $('#salle_hint').text('');
    });

    // 3. Lors de la sélection du jour, récupérer les horaires disponibles
    $('#jour_id').on('change', function() {
        var jourId = $(this).val();
        var classId = $('#class_id').val();
        var horaireSelect = $('#horaire_id');

        if (jourId && classId) {
            $.ajax({
                url: '{{ route("teacher.requests.get-horaires") }}',
                type: 'GET',
                data: { jour_id: jourId, class_id: classId },
                success: function(response) {
                    horaireSelect.empty().append('<option value="">-- Choisir l\'horaire --</option>');
                    if (response.horaires.length > 0) {
                        $.each(response.horaires, function(index, horaire) {
                            var startTime = horaire.start_time.substring(0, 5);
                            var endTime = horaire.end_time.substring(0, 5);
                            horaireSelect.append('<option value="' + horaire.id + '">' + startTime + ' - ' + endTime + '</option>');
                        });
                        $('#horaire_hint').text(response.horaires.length + ' horaire(s) disponible(s)');
                    } else {
                        $('#horaire_hint').text('Aucun horaire disponible ce jour');
                    }
                    horaireSelect.prop('disabled', false);
                },
                error: function() {
                    Swal.fire('Erreur!', 'Échec de récupération des horaires', 'error');
                }
            });
        } else {
            resetField('#horaire_id', '-- Choisir d\'abord le jour --');
        }

        resetField('#salle_de_classe_id', '-- Choisir d\'abord l\'horaire --');
        $('#salle_hint').text('');
    });

    // 4. Lors de la sélection de l'horaire, récupérer les salles disponibles
    $('#horaire_id').on('change', function() {
        var horaireId = $(this).val();
        var jourId = $('#jour_id').val();
        var salleSelect = $('#salle_de_classe_id');

        if (horaireId && jourId) {
            $.ajax({
                url: '{{ route("teacher.requests.get-salles") }}',
                type: 'GET',
                data: { jour_id: jourId, horaire_id: horaireId },
                success: function(response) {
                    salleSelect.empty().append('<option value="">-- Choisir la salle --</option>');
                    if (response.salles.length > 0) {
                        $.each(response.salles, function(index, salle) {
                            salleSelect.append('<option value="' + salle.id + '">' + salle.name + '</option>');
                        });
                        $('#salle_hint').text(response.salles.length + ' salle(s) disponible(s)');
                    } else {
                        $('#salle_hint').text('Aucune salle disponible à cet horaire');
                    }
                    salleSelect.prop('disabled', false);
                },
                error: function() {
                    Swal.fire('Erreur!', 'Échec de récupération des salles', 'error');
                }
            });
        } else {
            resetField('#salle_de_classe_id', '-- Choisir d\'abord l\'horaire --');
        }
    });

    function resetField(selector, placeholder) {
        $(selector).empty().append('<option value="">' + placeholder + '</option>').prop('disabled', true);
    }

    // Vérification avant envoi
    $('#requestForm').on('submit', function(e) {
        var form = $(this);
        var submitBtn = $('#submitBtn');

        // Vérifier que tous les champs sont remplis
        if (!$('#class_id').val() || !$('#subject_id').val() || !$('#trimester_id').val() ||
            !$('#jour_id').val() || !$('#horaire_id').val() || !$('#salle_de_classe_id').val()) {
            e.preventDefault();
            Swal.fire('Attention!', 'Veuillez remplir tous les champs obligatoires', 'warning');
            return false;
        }

        submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i> Envoi en cours...');
    });
});
</script>
@endsection
