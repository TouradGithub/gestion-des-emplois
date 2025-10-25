@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل مستوى التكوين</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('web.niveauformations.update', $niveauformation->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="nom" class="col-md-4 col-form-label text-md-end">الاسم</label>

                            <div class="col-md-6">
                                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom', $niveauformation->nom) }}" required autocomplete="nom" autofocus>

                                @error('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ordre" class="col-md-4 col-form-label text-md-end">الترتيب</label>

                            <div class="col-md-6">
                                <input id="ordre" type="text" class="form-control @error('ordre') is-invalid @enderror" name="ordre" value="{{ old('ordre', $niveauformation->ordre) }}" required>

                                @error('ordre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    تحديث
                                </button>
                                <a href="{{ route('web.niveauformations.index') }}" class="btn btn-secondary">
                                    إلغاء
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
