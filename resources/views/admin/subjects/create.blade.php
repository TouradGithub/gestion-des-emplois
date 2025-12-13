@extends('layouts.masters.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">
            <i class="fas fa-plus-circle"></i> Ajouter une Matière
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('web.subjects.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label><i class="fas fa-book"></i> Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required placeholder="Nom de la matière">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label><i class="fas fa-barcode"></i> Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code') }}" required placeholder="Code unique">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label><i class="fas fa-tag"></i> Type <span class="text-danger">*</span></label>
                        <select name="subject_type_id" class="form-control @error('subject_type_id') is-invalid @enderror" required>
                            @foreach($subjectTypes as $type)
                                <option value="{{ $type->id }}"
                                        data-color="{{ $type->color }}"
                                        {{ old('subject_type_id', 1) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label><i class="fas fa-graduation-cap"></i> Spécialité</label>
                        <select name="specialite_id" class="form-control">
                            <option value="">-- Aucune --</option>
                            @foreach($specialites as $specialite)
                                <option value="{{ $specialite->id }}" {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                    {{ $specialite->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('web.subjects.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
