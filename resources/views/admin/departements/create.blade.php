@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.departements') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Créer un département
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('web.departements.store') }}" method="POST" novalidate="novalidate">
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
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Nom" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label>Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" placeholder="Code" value="{{ old('code') }}" required>
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
