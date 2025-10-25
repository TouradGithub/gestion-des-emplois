@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('تعديل الصف') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('web.classes.update', $classe->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="niveau_pedagogique_id" class="col-md-4 col-form-label text-md-right">{{ __('المستوى البيداغوجي') }}</label>

                            <div class="col-md-6">
                                <select id="niveau_pedagogique_id" class="form-control @error('niveau_pedagogique_id') is-invalid @enderror" name="niveau_pedagogique_id" required>
                                    <option value="">اختر المستوى البيداغوجي</option>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}" {{ (old('niveau_pedagogique_id', $classe->niveau_pedagogique_id) == $niveau->id) ? 'selected' : '' }}>
                                            {{ $niveau->nom }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('niveau_pedagogique_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="specialite_id" class="col-md-4 col-form-label text-md-right">{{ __('التخصص') }}</label>

                            <div class="col-md-6">
                                <select id="specialite_id" class="form-control @error('specialite_id') is-invalid @enderror" name="specialite_id" required>
                                    <option value="">اختر التخصص</option>
                                    @foreach($specialites as $specialite)
                                        <option value="{{ $specialite->id }}" {{ (old('specialite_id', $classe->specialite_id) == $specialite->id) ? 'selected' : '' }}>
                                            {{ $specialite->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('specialite_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="annee_id" class="col-md-4 col-form-label text-md-right">{{ __('السنة الدراسية') }}</label>

                            <div class="col-md-6">
                                <select id="annee_id" class="form-control @error('annee_id') is-invalid @enderror" name="annee_id" required>
                                    <option value="">اختر السنة الدراسية</option>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}" {{ (old('annee_id', $classe->annee_id) == $annee->id) ? 'selected' : '' }}>
                                            {{ $annee->annee }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('annee_id')
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
                                <a href="{{ route('web.classes.index') }}" class="btn btn-secondary">
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
