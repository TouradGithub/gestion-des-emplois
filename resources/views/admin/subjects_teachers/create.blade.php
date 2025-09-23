
@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.teachers') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
               Affecter Proff a Matiere
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3 student-registration-form" enctype="multipart/form-data" action="{{ route('web.subjects_teachers.store') }}" method="POST" novalidate="novalidate">
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
                                    <label>Proffesseur <span class="text-danger">*</span></label>
                                    <select name="teacher_id" class="form-control" required>
                                        <option value="">{{ __('genirale.choose') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value=" {{ $teacher->id }}" >{{ $teacher->name }}</option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Matiere <span class="text-danger">*</span></label>
                                    <select name="subject_id" class="form-control" required>
                                        <option value="">{{ __('genirale.choose') }}</option>
                                        @foreach($subjects as $subject)

                                            <option value=" {{ $subject->id }}" >{{ $subject->name }}</option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Trimester <span class="text-danger">*</span></label>
                                    <select name="trimester_id" class="form-control" required>
                                        <option value="">{{ __('genirale.choose') }}</option>
                                        @foreach($trimesters as $trimester)

                                            <option value=" {{ $trimester->id }}" >{{ $trimester->name }}</option>

                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>Classe <span class="text-danger">*</span></label>
                                    <select name="class_id" class="form-control" required>
                                        <option value="">{{ __('genirale.choose') }}</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                        @endforeach
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
