@extends('layouts.masters.master')

@section('title')
    {{ __('messages.edit_schedule') }}
@endsection

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .form-select {
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        background-color: #fff;
    }
    
    .form-select option {
        padding: 10px;
        background-color: #fff;
        color: #5a5c69;
    }
    
    .form-select option:hover {
        background-color: #f8f9fc;
    }
    
    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .form-label i {
        color: #4e73df;
    }
    
    .form-text {
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #6c82f4);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }
    
    .btn-secondary {
        background: linear-gradient(45deg, #6c757d, #8a95a5);
        border: none;
        color: white;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }
    
    /* Multiple Select Styling */
    select[multiple] {
        min-height: 120px;
        padding: 8px;
    }
    
    select[multiple] option {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 4px;
        background-color: #fff;
        color: #5a5c69;
    }
    
    select[multiple] option:checked {
        background-color: #4e73df;
        color: white;
    }
    
    select[multiple] option:hover {
        background-color: #f8f9fc;
    }
    
    select[multiple] option:checked:hover {
        background-color: #3d5bc7;
    }
    
    /* Select2 Custom Styling */
    .select2-container--bootstrap-5 .select2-selection {
        border: 2px solid #e3e6f0;
        border-radius: 8px;
        min-height: 45px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple {
        min-height: 50px;
        padding: 5px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        border: 1px solid #4e73df;
        border-radius: 15px;
        color: white;
        padding: 2px 8px;
        margin: 2px;
        font-size: 12px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
        font-weight: bold;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffc107;
    }
    
    .select2-dropdown {
        border: 2px solid #4e73df;
        border-radius: 8px;
    }
    
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #4e73df;
        color: white;
    }
</style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.edit_schedule') }}</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('web.emplois.update', $emploi->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label">
                                        <i class="mdi mdi-school"></i> {{ __('messages.class') }}
                                    </label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" 
                                            id="class_id" name="class_id" required>
                                        <option value="">🎓 {{ __('messages.select_class') }}</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" 
                                                {{ old('class_id', $emploi->class_id) == $classe->id ? 'selected' : '' }}>
                                                📚 {{ $classe->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="subject_id" class="form-label">
                                        <i class="mdi mdi-book-open-variant"></i> {{ __('messages.subject') }}
                                    </label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" required>
                                        <option value="">📖 {{ __('messages.select_subject') }}</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $emploi->subject_id) == $subject->id ? 'selected' : '' }}>
                                                📚 {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="teacher_id" class="form-label">
                                        <i class="mdi mdi-account-tie"></i> {{ __('messages.teacher') }}
                                    </label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" name="teacher_id" required>
                                        <option value="">👨‍🏫 {{ __('messages.select_teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                {{ old('teacher_id', $emploi->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                👨‍🏫 {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="trimester_id" class="form-label">
                                        <i class="mdi mdi-calendar-range"></i> {{ __('messages.trimester') }}
                                    </label>
                                    <select class="form-select @error('trimester_id') is-invalid @enderror" 
                                            id="trimester_id" name="trimester_id" required>
                                        <option value="">📅 {{ __('messages.select_trimester') }}</option>
                                        @foreach($trimesters as $trimester)
                                            <option value="{{ $trimester->id }}" 
                                                {{ old('trimester_id', $emploi->trimester_id) == $trimester->id ? 'selected' : '' }}>
                                                📅 {{ $trimester->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('trimester_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="annee_id" class="form-label">
                                        <i class="mdi mdi-calendar-account"></i> {{ __('messages.school_year') }}
                                    </label>
                                    <select class="form-select @error('annee_id') is-invalid @enderror" 
                                            id="annee_id" name="annee_id" required>
                                        <option value="">📚 {{ __('messages.select_year') }}</option>
                                        @foreach($anneescolaires as $annee)
                                            <option value="{{ $annee->id }}" 
                                                {{ old('annee_id', $emploi->annee_id) == $annee->id ? 'selected' : '' }}>
                                                🎓 {{ $annee->annee }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="jour_id" class="form-label">
                                        <i class="mdi mdi-calendar-today"></i> {{ __('messages.day') }}
                                    </label>
                                    <select class="form-select @error('jour_id') is-invalid @enderror" 
                                            id="jour_id" name="jour_id" required>
                                        <option value="">📅 {{ __('messages.select_day') }}</option>
                                        @foreach($jours as $jour)
                                            <option value="{{ $jour->id }}" 
                                                {{ old('jour_id', $emploi->jour_id) == $jour->id ? 'selected' : '' }}>
                                                📅 {{ $jour->libelle_fr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jour_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="horaires" class="form-label">
                                        <i class="mdi mdi-clock-outline"></i> {{ __('messages.schedule_times') }}
                                    </label>
                                    <select class="form-select @error('horaires') is-invalid @enderror" 
                                            id="horaires" name="horaires[]" multiple required>
                                        @foreach($horaires as $horaire)
                                            <option value="{{ $horaire->id }}" 
                                                {{ in_array($horaire->id, old('horaires', $selectedHoraires ?? [])) ? 'selected' : '' }}>
                                                🕐 {{ $horaire->start_time ?? $horaire->libelle_fr }} 
                                                @if($horaire->end_time)
                                                    - {{ $horaire->end_time }}
                                                @endif
                                                @if($horaire->libelle_fr)
                                                    ({{ $horaire->libelle_fr }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('horaires')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="mdi mdi-information-outline"></i> {{ __('messages.ctrl_select_help') }}
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('messages.update') }}
                                    </button>
                                    <a href="{{ route('web.emplois.index') }}" class="btn btn-secondary">
                                        {{ __('messages.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for horaires with multiple selection
    $('#horaires').select2({
        theme: 'bootstrap-5',
        placeholder: '🕐 {{ __('messages.select_times') }}',
        allowClear: true,
        multiple: true,
        width: '100%',
        templateResult: function(option) {
            if (!option.id) {
                return option.text;
            }
            
            var optionElement = $(option.element);
            var text = option.text;
            
            // Add some styling to the dropdown options
            return $('<span><i class="mdi mdi-clock-outline"></i> ' + text + '</span>');
        },
        templateSelection: function(option) {
            if (!option.id) {
                return option.text;
            }
            
            // Style the selected items
            return $('<span class="badge bg-primary"><i class="mdi mdi-clock"></i> ' + option.text + '</span>');
        }
    });

    // Initialize Select2 for other dropdowns
    $('.form-select:not(#horaires)').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: false
    });
});
</script>
@endsection