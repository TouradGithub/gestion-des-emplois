@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.teachers') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Creer enseignant
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3 student-registration-form" enctype="multipart/form-data" action="{{ route('web.teachers.store') }}" method="POST" novalidate="novalidate">
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
                                    <label>Nom<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Nom" value="{{ old('nom') }}">
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>NNI <span class="text-danger">*</span></label>
                                    <input type="text" name="nni" class="form-control" placeholder="NNI" value="{{ old('nni') }}">
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Tel</label>
                                    <input type="number" name="phone" class="form-control" placeholder="phone" value="{{ old('phone') }}" min="0">
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email" value="{{ old('email') }}" >
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                        <label>Genre <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">{{ __('genirale.choose') }}</option>
                                            <option value="1" {{ old('gender') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                            <option value="0" {{ old('gender') == 'femme' ? 'selected' : '' }}>Femme</option>
                                        </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Image</label>
                                    <input type="file" name="image" class="file-upload-default"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled placeholder="Image" />
                                        <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-theme" type="button">Upload</button>
                                </span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Date de naissance<span class="text-danger">*</span></label>
                                    <input type="text" name="dob" class="datepicker-popup-no-future form-control" placeholder="Date de naissance" value="{{ old('dob') }}">
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
