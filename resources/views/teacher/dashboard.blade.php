@extends('layouts.teacher.master')

@section('title', __('teacher.dashboard'))

@section('breadcrumb')
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card welcome-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="text-white mb-2">
                            <i class="mdi mdi-account-tie me-2"></i>
                            {{ __('teacher.welcome_message') }}
                        </h2>
                        <h4 class="text-white-75">{{ $teacher->name }}</h4>
                        <p class="text-white-50 mb-0">{{ __('teacher.dashboard_description') }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="mdi mdi-school display-1 text-white-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if(isset($stats))
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="mdi mdi-book-open-variant display-4 mb-2"></i>
                <h3>{{ $stats['total_subjects'] }}</h3>
                <p class="mb-0">{{ __('teacher.total_subjects') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="mdi mdi-clock-outline display-4 mb-2"></i>
                <h3>{{ $stats['total_hours'] }}</h3>
                <p class="mb-0">{{ __('teacher.total_hours') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="mdi mdi-calendar-check display-4 mb-2"></i>
                <h3>{{ $stats['this_month_pointages'] }}</h3>
                <p class="mb-0">{{ __('teacher.this_month_pointages') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="mdi mdi-chart-line display-4 mb-2"></i>
                <h3>{{ $stats['attendance_rate'] }}%</h3>
                <p class="mb-0">{{ __('teacher.attendance_rate') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Departments Section -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-book-open-variant me-2"></i>
                    {{ __('teacher.my_subjects') }}
                </h5>
                @if(isset($subjectTeachers) && count($subjectTeachers) > 0)
                <a href="{{ route('teacher.departments') }}" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-eye"></i> {{ __('messages.view_all') }}
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(isset($subjectTeachers) && count($subjectTeachers) > 0)
                    <div class="row">
                        @foreach(($subjectTeachers ?? collect())->take(2) as $subjectTeacher)
                        <div class="col-md-6 mb-3">
                            <div class="card department-card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="mdi mdi-book-open-variant text-primary me-2"></i>
                                        {{ $subjectTeacher->subject->name ?? 'N/A' }}
                                    </h6>
                                    <p class="card-text text-muted small">
                                        {{ __('teacher.department') }}: {{ $subjectTeacher->subject->specialite->departement->name ?? 'N/A' }}
                                    </p>
                                    <p class="card-text text-muted small">
                                        {{ __('teacher.class') }}: {{ $subjectTeacher->classe->nom ?? 'N/A' }}
                                    </p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('teacher.schedule', $subjectTeacher->classe->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="mdi mdi-calendar-clock"></i>
                                            {{ __('teacher.view_schedule') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(isset($subjectTeachers) && count($subjectTeachers) > 2)
                    <div class="text-center">
                        <small class="text-muted">{{ __('messages.and_more', ['count' => count($subjectTeachers ?? []) - 2]) }}</small>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-domain-off display-4 text-muted"></i>
                        <h6 class="text-muted mt-3">{{ __('teacher.no_departments') }}</h6>
                        <p class="text-muted small">{{ __('teacher.contact_admin') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-link-variant me-2"></i>
                    {{ __('messages.quick_links') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('teacher.departments') }}" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-book-open-variant text-primary me-2"></i>
                        {{ __('teacher.my_subjects') }}
                        <i class="mdi mdi-chevron-left float-end"></i>
                    </a>

                    <a href="{{ route('teacher.pointages') }}" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-clock-check text-success me-2"></i>
                        {{ __('teacher.my_pointages') }}
                        <i class="mdi mdi-chevron-left float-end"></i>
                    </a>

                    <a href="{{ route('teacher.profile') }}" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-account-edit text-warning me-2"></i>
                        {{ __('teacher.profile') }}
                        <i class="mdi mdi-chevron-left float-end"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Personal Info Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-account-circle me-2"></i>
                    {{ __('teacher.personal_info') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>{{ __('teacher.full_name') }}:</strong><br>
                    <span class="text-muted">{{ $teacher->name }}</span>
                </div>

                <div class="mb-2">
                    <strong>{{ __('teacher.email') }}:</strong><br>
                    <span class="text-muted">{{ $teacher->email }}</span>
                </div>

                @if($teacher->phone)
                <div class="mb-2">
                    <strong>{{ __('teacher.phone') }}:</strong><br>
                    <span class="text-muted">{{ $teacher->phone }}</span>
                </div>
                @endif

                @if($teacher->speciality)
                <div class="mb-2">
                    <strong>{{ __('teacher.speciality') }}:</strong><br>
                    <span class="text-muted">{{ $teacher->speciality }}</span>
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('teacher.profile') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="mdi mdi-account-edit"></i>
                        {{ __('teacher.update_profile') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .text-white-25 {
        opacity: 0.25;
    }
    .text-white-50 {
        opacity: 0.5;
    }
    .text-white-75 {
        opacity: 0.75;
    }

    .stats-card .display-4 {
        opacity: 0.8;
    }

    .department-card {
        height: 100%;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
