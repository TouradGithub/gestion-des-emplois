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

<!-- Sessions Summary Table -->
@if(isset($hoursSummary) && count($hoursSummary) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card sessions-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-calendar-clock me-2"></i>
                    Récapitulatif des séances
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><i class="mdi mdi-book-open-variant me-1"></i> Matière</th>
                                <th><i class="mdi mdi-account-group me-1"></i> Classe</th>
                                <th><i class="mdi mdi-calendar-text me-1"></i> Trimestre</th>
                                <th class="text-center"><i class="mdi mdi-clock-outline me-1"></i> Séances programmées</th>
                                <th class="text-center"><i class="mdi mdi-check-circle me-1"></i> Réalisées</th>
                                <th class="text-center"><i class="mdi mdi-clock-alert me-1"></i> Restantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hoursSummary as $item)
                            <tr>
                                <td><strong>{{ $item['subject'] }}</strong></td>
                                <td>
                                    <span class="badge bg-primary">{{ $item['classe'] }}</span>
                                </td>
                                <td>{{ $item['trimester'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item['heures_trimestre'] }}h</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $item['heures_effectuees'] ?? 0 }}h</span>
                                </td>
                                <td class="text-center">
                                    @if(($item['heures_restantes'] ?? 0) > 0)
                                        <span class="badge bg-warning text-dark">{{ $item['heures_restantes'] }}h</span>
                                    @else
                                        <span class="badge bg-success"><i class="mdi mdi-check"></i> Terminé</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-center">
                                    <span class="badge bg-dark">{{ $stats['total_hours_trimestre'] ?? 0 }}h</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-success">{{ $stats['total_hours_effectuees'] ?? 0 }}h</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-warning text-dark">{{ ($stats['total_hours_trimestre'] ?? 0) - ($stats['total_hours_effectuees'] ?? 0) }}h</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


<!-- Departments Section -->
<div class="row">
   

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

    .department-card {
        height: 100%;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }

    /* Sessions Card Styles */
    .sessions-card {
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .sessions-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 15px 15px 0 0;
        padding: 15px 20px;
    }

    .sessions-card .table {
        margin-bottom: 0;
    }

    .sessions-card .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 12px;
    }

    .sessions-card .table tbody td {
        vertical-align: middle;
        padding: 12px;
    }

    .sessions-card .table tfoot th {
        background: #f8f9fa;
        padding: 12px;
    }
</style>
@endsection
