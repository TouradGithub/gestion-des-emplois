@extends('layouts.masters.master')

@section('title', 'تعديل الصف')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">تعديل الصف</h3>
        </div>

        <div class="card">
            <div class="card-body">
                    <form method="POST" action="{{ route('web.classes.update', $class->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" value="{{$class->nom}}" class="form-control" required>
                        </div>


                        <div class="form-group row">
                            <label for="niveau_pedagogique_id" class="col-md-4 col-form-label text-md-right">Niveau</label>

                            <div class="col-md-6">
                                <select id="niveau_pedagogique_id" class="form-control @error('niveau_pedagogique_id') is-invalid @enderror" name="niveau_pedagogique_id" required>
                                    <option value="">Niveau</option>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau->id }}" {{ (old('niveau_pedagogique_id', $class->niveau_pedagogique_id) == $niveau->id) ? 'selected' : '' }}>
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
                            <label for="specialite_id" class="col-md-4 col-form-label text-md-right">Specialite</label>

                            <div class="col-md-6">
                                <select id="specialite_id" class="form-control @error('specialite_id') is-invalid @enderror" name="specialite_id" required>
                                    <option value=""> Specialite</option>
                                    @foreach($specialites as $specialite)
                                        <option value="{{ $specialite->id }}" {{ (old('specialite_id', $class->specialite_id) == $specialite->id) ? 'selected' : '' }}>
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
                            <label for="annee_id" class="col-md-4 col-form-label text-md-right">Annee</label>

                            <div class="col-md-6">
                                <select id="annee_id" class="form-control @error('annee_id') is-invalid @enderror" name="annee_id" required>
                                    <option value="">  Selectinnez annee</option>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}" {{ (old('annee_id', $class->annee_id) == $annee->id) ? 'selected' : '' }}>
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
                                    Mettre à jour
                                </button>
                                <a href="{{ route('web.classes.index') }}" class="btn btn-secondary">
                                   annuler
                                </a>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection

