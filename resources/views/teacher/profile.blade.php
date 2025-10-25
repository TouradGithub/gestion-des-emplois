@extends('layouts.teacher.master')

@section('title', __('teacher.profile'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('teacher.profile') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-account-edit me-2"></i>
                    {{ __('teacher.personal_info') }}
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('teacher.full_name') }}</label>
                            <input type="text" class="form-control" value="{{ $teacher->name }}" readonly>
                            <small class="text-muted">{{ __('teacher.cannot_edit') }}</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('teacher.email') }}</label>
                            <input type="email" class="form-control" value="{{ $teacher->email }}" readonly>
                            <small class="text-muted">{{ __('teacher.cannot_edit') }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('teacher.phone') }}</label>
                            <input type="text" class="form-control" value="{{ $teacher->phone ?? '-' }}" readonly>
                            <small class="text-muted">{{ __('teacher.cannot_edit') }}</small>
                        </div>

                        @if($teacher->nni)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.nni') }}</label>
                            <input type="text" class="form-control" value="{{ $teacher->nni }}" readonly>
                            <small class="text-muted">{{ __('teacher.cannot_edit') }}</small>
                        </div>
                        @endif
                    </div>

                    @if($teacher->speciality)
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('teacher.speciality') }}</label>
                            <input type="text" class="form-control" value="{{ $teacher->speciality }}" readonly>
                            <small class="text-muted">{{ __('teacher.cannot_edit') }}</small>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-account-circle me-2"></i>
                    {{ __('messages.account_info') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ __('messages.user_type') }}:</strong><br>
                    <span class="badge bg-primary">
                        <i class="mdi mdi-school"></i>
                        {{ __('teacher.teacher_portal') }}
                    </span>
                </div>

                @if($teacher->created_at)
                <div class="mb-3">
                    <strong>{{ __('messages.member_since') }}:</strong><br>
                    <span class="text-muted">
                        {{ Carbon\Carbon::parse($teacher->created_at)->format('Y-m-d') }}
                    </span>
                </div>
                @endif

                <div class="mb-3">
                    <strong>{{ __('messages.last_login') }}:</strong><br>
                    <span class="text-muted">
                        {{ auth()->user()->updated_at ? Carbon\Carbon::parse(auth()->user()->updated_at)->diffForHumans() : '-' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-lightning-bolt me-2"></i>
                    {{ __('messages.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-view-dashboard"></i>
                        {{ __('teacher.dashboard') }}
                    </a>

                    <a href="{{ route('teacher.departments') }}" class="btn btn-outline-info">
                        <i class="mdi mdi-book-open-variant"></i>
                        {{ __('teacher.my_subjects') }}
                    </a>
                    <a href="{{ route('teacher.pointages') }}" class="btn btn-outline-success">
                        <i class="mdi mdi-clock-check"></i>
                        {{ __('teacher.my_pointages') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Admin -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-help-circle me-2"></i>
                    {{ __('messages.need_help') }}
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    {{ __('messages.contact_admin_info') }}
                </p>

                <div class="alert alert-info">
                    <i class="mdi mdi-information me-2"></i>
                    <strong>{{ __('messages.note') }}:</strong><br>
                    {{ __('teacher.read_only_access') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
