@extends('layouts.teacher.master')

@section('title', 'Demander une Attestation')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('teacher.attestations.index') }}">Attestations</a></li>
<li class="breadcrumb-item active">Nouvelle demande</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="mdi mdi-certificate me-2"></i>
                    Demander une Attestation
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.attestations.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Type d'attestation <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check card p-3 {{ old('type') == 'travail' ? 'border-primary' : '' }}">
                                    <input class="form-check-input" type="radio" name="type" id="type_travail"
                                           value="travail" {{ old('type', 'travail') == 'travail' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="type_travail">
                                        <i class="mdi mdi-briefcase-outline mdi-24px text-primary"></i>
                                        <h6 class="mb-1 mt-2">Attestation de travail</h6>
                                        <small class="text-muted">Confirme que vous travaillez dans notre établissement</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check card p-3 {{ old('type') == 'salaire' ? 'border-primary' : '' }}">
                                    <input class="form-check-input" type="radio" name="type" id="type_salaire"
                                           value="salaire" {{ old('type') == 'salaire' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="type_salaire">
                                        <i class="mdi mdi-cash-multiple mdi-24px text-success"></i>
                                        <h6 class="mb-1 mt-2">Attestation de salaire</h6>
                                        <small class="text-muted">Indique votre rémunération mensuelle</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check card p-3 {{ old('type') == 'experience' ? 'border-primary' : '' }}">
                                    <input class="form-check-input" type="radio" name="type" id="type_experience"
                                           value="experience" {{ old('type') == 'experience' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="type_experience">
                                        <i class="mdi mdi-school-outline mdi-24px text-info"></i>
                                        <h6 class="mb-1 mt-2">Attestation d'expérience</h6>
                                        <small class="text-muted">Détaille votre expérience professionnelle</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="motif" class="form-label fw-bold">Motif de la demande (optionnel)</label>
                        <textarea name="motif" id="motif" class="form-control @error('motif') is-invalid @enderror"
                                  rows="3" placeholder="Précisez le motif de votre demande...">{{ old('motif') }}</textarea>
                        @error('motif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Exemple: Pour dossier de visa, pour demande de crédit bancaire, etc.</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>Note:</strong> Votre demande sera traitée dans les plus brefs délais.
                        Une fois approuvée, vous pourrez télécharger votre attestation au format PDF.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.attestations.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-send me-1"></i> Soumettre la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .form-check.card {
        cursor: pointer;
        transition: all 0.3s;
    }
    .form-check.card:hover {
        border-color: #667eea !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .form-check-input:checked + .form-check-label {
        color: #667eea;
    }
    .form-check.card:has(.form-check-input:checked) {
        border-color: #667eea !important;
        background-color: rgba(102, 126, 234, 0.05);
    }
</style>
@endsection
