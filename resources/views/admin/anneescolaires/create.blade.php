@extends('layouts.masters.master')

@section('title')
    {{ __('Année Scolaire') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer une année scolaire</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3" action="{{ route('web.anneescolaires.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Année <span class="text-danger">*</span></label>
                                    <input type="text" name="annee" class="form-control" placeholder="2024-2025" value="{{ old('annee') }}">
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Date Début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}">
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Date Fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin') }}">
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Active ?</label>
                                    <select name="is_active" class="form-control">
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non</option>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Oui</option>
                                    </select>
                                </div>
                            </div>

                            <input class="btn btn-theme" type="submit" value="Enregistrer">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
