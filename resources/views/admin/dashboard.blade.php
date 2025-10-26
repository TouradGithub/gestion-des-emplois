@extends('layouts.masters.master')

@section('title', __('messages.dashboard'))

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-view-dashboard"></i>
            </span>
            {{ __('messages.dashboard') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('messages.dashboard') }}</li>
            </ul>
        </nav>
    </div>

    <!-- Welcome Message -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient-primary">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-white mb-2">
                                {{ __('messages.welcome') }} {{ auth()->user()->name }}
                            </h4>
                            <p class="text-white-75 mb-0">
                                {{ __('messages.admin_dashboard_welcome') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="mdi mdi-account-supervisor-circle display-1 text-white-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <!-- Total Teachers -->
        <div class="col-lg-3 col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_teachers'] }}</h4>
                            <p class="text-muted mb-0">{{ __('messages.teachers') }}</p>
                        </div>
                        <div class="icon-circle bg-primary-light">
                            <i class="mdi mdi-account-tie text-primary"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                    </div>
                    <small class="text-muted">+{{ $monthlyStats['new_teachers_this_month'] }} {{ __('messages.this_month') }}</small>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="col-lg-3 col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_classes'] }}</h4>
                            <p class="text-muted mb-0">{{ __('messages.classes') }}</p>
                        </div>
                        <div class="icon-circle bg-success-light">
                            <i class="mdi mdi-school text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                    <small class="text-muted">{{ $stats['total_departments'] }} {{ __('messages.departments') }}</small>
                </div>
            </div>
        </div>

        <!-- Total Subjects -->
        <div class="col-lg-3 col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_subjects'] }}</h4>
                            <p class="text-muted mb-0">{{ __('messages.subjects') }}</p>
                        </div>
                        <div class="icon-circle bg-warning-light">
                            <i class="mdi mdi-book-open-variant text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 90%"></div>
                    </div>
                    <small class="text-muted">{{ $stats['total_specialities'] }} {{ __('messages.specialities') }}</small>
                </div>
            </div>
        </div>

        <!-- Total Pointages -->
        <div class="col-lg-3 col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_pointages'] }}</h4>
                            <p class="text-muted mb-0">{{ __('pointages.pointages') }}</p>
                        </div>
                        <div class="icon-circle bg-info-light">
                            <i class="mdi mdi-clock-check text-info"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                    <small class="text-muted">+{{ $monthlyStats['pointages_this_month'] }} {{ __('messages.this_month') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('messages.quick_actions') }}</h4>
                    <div class="d-grid gap-2">
                        <a href="{{ route('web.teachers.create') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-account-plus me-2"></i>
                            {{ __('messages.add_teacher') }}
                        </a>
                        <a href="{{ route('web.classes.create') }}" class="btn btn-outline-success">
                            <i class="mdi mdi-school me-2"></i>
                            {{ __('messages.add_class') }}
                        </a>
                        <a href="{{ route('web.subjects.create') }}" class="btn btn-outline-warning">
                            <i class="mdi mdi-book-plus me-2"></i>
                            {{ __('messages.add_subject') }}
                        </a>
                        <a href="{{ route('web.pointages.create') }}" class="btn btn-outline-info">
                            <i class="mdi mdi-clock-plus me-2"></i>
                            {{ __('pointages.nouveau_pointage') }}
                        </a>
                        <a href="{{ route('web.emplois.create') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-calendar-plus me-2"></i>
                            {{ __('messages.add_schedule') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Teachers & Department Stats -->
    <div class="row">
        <!-- Recent Teachers -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ __('messages.recent_teachers') }}</h4>
                        <a href="{{ route('web.teachers.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('messages.view_all') }}
                        </a>
                    </div>

                    @if($recentTeachers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentTeachers as $teacher)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-primary-light me-3">
                                            <i class="mdi mdi-account text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $teacher->user->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $teacher->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $teacher->created_at ? $teacher->created_at->diffForHumans() : __('messages.unknown') }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">{{ __('messages.no_recent_teachers') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Department Statistics -->
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ __('messages.departments_overview') }}</h4>
                        <a href="{{ route('web.departements.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('messages.view_all') }}
                        </a>
                    </div>

                    @if($departmentStats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($departmentStats as $department)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $department->name }}</h6>
                                        <small class="text-muted">{{ $department->specialities_count }} {{ __('messages.specialities') }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $department->specialities_count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">{{ __('messages.no_departments') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Teachers Stats -->
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('messages.active_teachers_stats') }}</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-success-light me-3">
                                    <i class="mdi mdi-account-check text-success"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $activeTeachers }}</h5>
                                    <p class="text-muted mb-0">{{ __('messages.active_teachers_30_days') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-warning-light me-3">
                                    <i class="mdi mdi-calendar-clock text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $stats['total_emplois'] }}</h5>
                                    <p class="text-muted mb-0">{{ __('messages.total_schedules') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-info-light me-3">
                                    <i class="mdi mdi-account-group text-info"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $stats['total_users'] }}</h5>
                                    <p class="text-muted mb-0">{{ __('messages.total_users') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    // Auto refresh every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
</script>
@endsection

@section('css')
<style>
    .icon-circle {
        width: 50px;
        height: 50px;
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

    .bg-warning-light {
        background-color: rgba(246, 194, 62, 0.1);
    }

    .bg-info-light {
        background-color: rgba(54, 185, 204, 0.1);
    }

    .text-white-25 {
        opacity: 0.25;
    }

    .text-white-75 {
        opacity: 0.75;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .progress {
        background-color: rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
