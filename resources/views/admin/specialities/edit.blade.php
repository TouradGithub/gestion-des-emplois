@extends('layouts.masters.master')

@section('title', 'تعديل التخصص')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">تعديل التخصص</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('تعديل التخصص') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('web.specialities.update', $speciality->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('اسم التخصص') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $speciality->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('رمز التخصص') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $speciality->code) }}" required autocomplete="code">

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="departement_id" class="col-md-4 col-form-label text-md-right">{{ __('القسم') }}</label>

                            <div class="col-md-6">
                                <select id="departement_id" class="form-control @error('departement_id') is-invalid @enderror" name="departement_id" required>
                                    <option value="">اختر القسم</option>
                                    @foreach($departements as $dept)
                                        <option value="{{ $dept->id }}" {{ (old('departement_id', $speciality->departement_id) == $dept->id) ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('departement_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="niveau_formation_id" class="col-md-4 col-form-label text-md-right">{{ __('مستوى التكوين') }}</label>

                            <div class="col-md-6">
                                <select id="niveau_formation_id" class="form-control @error('niveau_formation_id') is-invalid @enderror" name="niveau_formation_id" required>
                                    <option value="">اختر مستوى التكوين</option>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}" {{ (old('niveau_formation_id', $speciality->niveau_formation_id) == $niveau->id) ? 'selected' : '' }}>
                                            {{ $niveau->nom }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('niveau_formation_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('تحديث') }}
                                </button>
                                <a href="{{ route('web.specialities.index') }}" class="btn btn-secondary">
                                    {{ __('إلغاء') }}
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
