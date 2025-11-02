@extends('layouts.masters.master')

@section('title', __('messages.add_student'))

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.add_student') }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('web.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('web.students.index') }}">{{ __('messages.list_students') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.add_student') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('messages.add_new_student') }}</h4>
                        <p class="card-description">{{ __('messages.fill_student_information') }}</p>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('web.students.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- NNI Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="nni" class="form-label">
                                        <i class="mdi mdi-card-account-details"></i> {{ __('messages.nni') }} *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('nni') is-invalid @enderror"
                                           id="nni"
                                           name="nni"
                                           value="{{ old('nni') }}"
                                           placeholder="{{ __('messages.enter_nni') }}"
                                           required>
                                    @error('nni')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Full Name Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="fullname" class="form-label">
                                        <i class="mdi mdi-account"></i> {{ __('messages.fullname') }} *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('fullname') is-invalid @enderror"
                                           id="fullname"
                                           name="fullname"
                                           value="{{ old('fullname') }}"
                                           placeholder="{{ __('messages.enter_fullname') }}"
                                           required>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Parent Name Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="parent_name" class="form-label">
                                        <i class="mdi mdi-account-supervisor"></i> {{ __('messages.parent_name') }} *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('parent_name') is-invalid @enderror"
                                           id="parent_name"
                                           name="parent_name"
                                           value="{{ old('parent_name') }}"
                                           placeholder="{{ __('messages.enter_parent_name') }}"
                                           required>
                                    @error('parent_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="mdi mdi-phone"></i> {{ __('messages.phone') }} *
                                    </label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="{{ __('messages.enter_phone') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Class Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="class_id" class="form-label">
                                        <i class="mdi mdi-school"></i> {{ __('messages.class') }} *
                                    </label>
                                    <select class="form-select @error('class_id') is-invalid @enderror"
                                            id="class_id"
                                            name="class_id"
                                            required>
                                        <option value="">{{ __('messages.select_class') }}</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->nom }}
                                                @if($class->niveau)
                                                    - {{ $class->niveau->nom }}
                                                @endif
                                                @if($class->specialite)
                                                    - {{ $class->specialite->nom }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">
                                        <i class="mdi mdi-camera"></i> {{ __('messages.student_image') }}
                                    </label>
                                    <input type="file"
                                           class="form-control @error('image') is-invalid @enderror"
                                           id="image"
                                           name="image"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{ __('messages.image_help') }}</small>
                                </div>
                            </div>

                            <!-- Image Preview -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div id="image-preview" class="text-center" style="display: none;">
                                        <img id="preview-img" src="" class="rounded" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Account Info Notice -->
                            <div class="alert alert-info">
                                <i class="mdi mdi-information-outline"></i>
                                <strong>{{ __('messages.account_creation_notice') }}</strong><br>
                                {{ __('messages.account_creation_details') }}
                            </div>

                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> {{ __('messages.save_student') }}
                                    </button>
                                    <a href="{{ route('web.students.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left"></i> {{ __('messages.back') }}
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

@section('script')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            $('#preview-img').attr('src', e.target.result);
            $('#image-preview').show();
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        $('#image-preview').hide();
    }
}
</script>
@endsection
