@extends('layouts.masters.master')

@section('title')
    {{ __('messages.edit_teacher') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-account-edit"></i>
                </span>
                {{ __('messages.edit_teacher') }}
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('web.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('web.teachers.index') }}">{{ __('messages.teachers') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.edit_teacher') }}</li>
                </ul>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('messages.teacher_information') }}</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('web.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('messages.full_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name', $teacher->name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nni">{{ __('messages.nni') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nni" name="nni"
                                               value="{{ old('nni', $teacher->nni) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('messages.email') }}</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{ old('email', $teacher->email) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">{{ __('messages.phone') }}</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               value="{{ old('phone', $teacher->phone) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob">{{ __('messages.date_of_birth') }}</label>
                                        <input type="date" class="form-control" id="dob" name="dob"
                                               value="{{ old('dob', $teacher->dob) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">{{ __('messages.gender') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="">{{ __('messages.select_gender') }}</option>
                                            <option value="1" {{ old('gender', $teacher->gender) == '1' ? 'selected' : '' }}>
                                                {{ __('messages.male') }}
                                            </option>
                                            <option value="0" {{ old('gender', $teacher->gender) == '0' ? 'selected' : '' }}>
                                                {{ __('messages.female') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image">{{ __('messages.teacher_image') }}</label>
                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">

                                @if($teacher->image)
                                    <div class="mt-2">
                                        <small class="text-muted">{{ __('messages.current_image') }}:</small><br>
                                        <img src="{{ asset('storage/' . $teacher->image) }}"
                                             alt="Teacher Image" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-gradient-primary me-2">
                                    <i class="mdi mdi-content-save"></i>
                                    {{ __('messages.update') }}
                                </button>
                                <a href="{{ route('web.teachers.index') }}" class="btn btn-light">
                                    {{ __('messages.cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Teacher Stats -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('messages.teacher_stats') }}</h4>

                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-primary-light me-3">
                                <i class="mdi mdi-book-open text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $teacher->subjectTeachers->count() }}</h5>
                                <p class="text-muted mb-0">{{ __('messages.subjects_assigned') }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-success-light me-3">
                                <i class="mdi mdi-calendar-check text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $teacher->pointages->count() }}</h5>
                                <p class="text-muted mb-0">{{ __('pointages.total_pointages') }}</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info-light me-3">
                                <i class="mdi mdi-calendar-clock text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $teacher->created_at ? $teacher->created_at->format('d/m/Y') : __('messages.unknown') }}</h5>
                                <p class="text-muted mb-0">{{ __('messages.joined_date') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1);
    }

    .bg-success-light {
        background-color: rgba(28, 200, 138, 0.1);
    }

    .bg-info-light {
        background-color: rgba(54, 185, 204, 0.1);
    }
</style>
@endsection
