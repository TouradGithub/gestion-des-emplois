@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.niveauformations') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Créer un niveau de formation
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3" action="{{ route('web.niveauformations.store') }}" method="POST" novalidate>
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Nom du niveau <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control" placeholder="Nom du niveau" value="{{ old('nom') }}" required>
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Ordre <span class="text-danger">*</span></label>
                                    <input type="number" name="ordre" class="form-control" placeholder="Nom du niveau" value="{{ old('nom') }}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-theme">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
