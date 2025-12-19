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
                <h3>{{ $stats['total_hours_effectuees'] ?? 0 }}h / {{ $stats['total_hours_trimestre'] ?? 0 }}h</h3>
                <p class="mb-0">Heures (Effectuées / Trimestre)</p>
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

<!-- Hours Progress Section -->
@if(isset($hoursSummary) && count($hoursSummary) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card hours-progress-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-clock-check me-2"></i>
                    Suivi des heures par classe
                </h5>
                <div class="global-taux">
                    @php
                        $tauxGlobal = $stats['taux_global'] ?? 0;
                        $tauxClass = '';
                        $tauxIcon = '';
                        if ($tauxGlobal < 50) {
                            $tauxClass = 'taux-low';
                            $tauxIcon = 'mdi-alert-circle';
                        } elseif ($tauxGlobal < 100) {
                            $tauxClass = 'taux-medium';
                            $tauxIcon = 'mdi-progress-clock';
                        } elseif ($tauxGlobal == 100) {
                            $tauxClass = 'taux-complete';
                            $tauxIcon = 'mdi-check-circle';
                        } else {
                            $tauxClass = 'taux-exceeded';
                            $tauxIcon = 'mdi-alert';
                        }
                    @endphp
                    <span class="badge {{ $tauxClass }}">
                        <i class="mdi {{ $tauxIcon }} me-1"></i>
                        Taux global: {{ $tauxGlobal }}%
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Matière</th>
                                <th>Trimestre</th>
                                <th>Heures/Trimestre</th>
                                <th>Restantes</th>
                                <th>Progression</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hoursSummary as $item)
                            <tr>
                                <td><strong>{{ $item['classe'] }}</strong></td>
                                <td>{{ $item['subject'] }}</td>
                                <td>{{ $item['trimester'] }}</td>
                                <td>
                                    <span class="badge-hours">{{ $item['heures_trimestre'] }}h</span>
                                </td>
                                <td>
                                    @if($item['heures_restantes'] > 0)
                                        <span class="text-muted">{{ $item['heures_restantes'] }}h</span>
                                    @else
                                        <span class="text-success"><i class="mdi mdi-check"></i></span>
                                    @endif
                                </td>
                                <td style="width: 200px;">
                                    @php
                                        $taux = $item['taux'];
                                        $progressClass = '';
                                        if ($taux < 50) {
                                            $progressClass = 'progress-low';
                                        } elseif ($taux < 100) {
                                            $progressClass = 'progress-medium';
                                        } elseif ($taux == 100) {
                                            $progressClass = 'progress-complete';
                                        } else {
                                            $progressClass = 'progress-exceeded';
                                        }
                                    @endphp
                                    <div class="progress-wrapper">
                                        <div class="progress">
                                            <div class="progress-bar {{ $progressClass }}"
                                                 role="progressbar"
                                                 style="width: {{ min($taux, 100) }}%"
                                                 aria-valuenow="{{ $taux }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="progress-text">{{ $taux }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statut = $item['statut'];
                                        $statutBadge = '';
                                        $statutText = '';
                                        switch($statut) {
                                            case 'non_defini':
                                                $statutBadge = 'statut-undefined';
                                                $statutText = 'Non défini';
                                                break;
                                            case 'en_retard':
                                                $statutBadge = 'statut-late';
                                                $statutText = 'En retard';
                                                break;
                                            case 'en_cours':
                                                $statutBadge = 'statut-progress';
                                                $statutText = 'En cours';
                                                break;
                                            case 'complet':
                                                $statutBadge = 'statut-complete';
                                                $statutText = 'Complet';
                                                break;
                                            case 'depasse':
                                                $statutBadge = 'statut-exceeded';
                                                $statutText = 'Dépassé';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $statutBadge }}">{{ $statutText }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="hours-summary mt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="summary-item">
                                <i class="mdi mdi-clock-outline"></i>
                                <div class="summary-content">
                                    <span class="summary-value">{{ $stats['total_hours_trimestre'] ?? 0 }}h</span>
                                    <span class="summary-label">Total heures du trimestre</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-item">
                                <i class="mdi mdi-clock-check"></i>
                                <div class="summary-content">
                                    <span class="summary-value">{{ $stats['total_hours_effectuees'] ?? 0 }}h</span>
                                    <span class="summary-label">Total heures effectuées</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-item {{ $stats['taux_global'] >= 100 ? 'complete' : '' }}">
                                <i class="mdi mdi-percent"></i>
                                <div class="summary-content">
                                    <span class="summary-value">{{ $stats['taux_global'] ?? 0 }}%</span>
                                    <span class="summary-label">Taux de réalisation</span>
                                </div>
                            </div>
                        </div>
                    </div>
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

    .stats-card .display-4 {
        opacity: 0.8;
    }

    .department-card {
        height: 100%;
    }

    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }

    /* Hours Progress Card Styles */
    .hours-progress-card {
        border: 1px solid #e0e0e0;
        border-radius: 15px;
    }

    .hours-progress-card .card-header {
        background: #1a1a1a;
        color: #fff;
        border-radius: 15px 15px 0 0;
    }

    .hours-progress-card .table thead th {
        background: #f5f5f5;
        border-bottom: 2px solid #e0e0e0;
        font-weight: 600;
        color: #1a1a1a;
    }

    .badge-hours {
        background: #1a1a1a;
        color: #fff;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
    }

    .badge-hours-actual {
        background: #fff;
        color: #1a1a1a;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
        border: 1px solid #1a1a1a;
    }

    .badge-hours-secondary {
        background: #666;
        color: #fff;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
    }

    /* Progress Bar */
    .progress-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress {
        height: 10px;
        border-radius: 5px;
        background: #e0e0e0;
        flex: 1;
    }

    .progress-bar {
        border-radius: 5px;
    }

    .progress-low {
        background: #ccc;
    }

    .progress-medium {
        background: #666;
    }

    .progress-complete {
        background: #1a1a1a;
    }

    .progress-exceeded {
        background: #333;
    }

    .progress-text {
        font-weight: 600;
        min-width: 45px;
        color: #1a1a1a;
    }

    /* Status Badges */
    .statut-undefined {
        background: #f5f5f5;
        color: #666;
        border: 1px solid #ccc;
    }

    .statut-late {
        background: #fff;
        color: #1a1a1a;
        border: 1px solid #1a1a1a;
    }

    .statut-progress {
        background: #666;
        color: #fff;
    }

    .statut-complete {
        background: #1a1a1a;
        color: #fff;
    }

    .statut-exceeded {
        background: #333;
        color: #fff;
    }

    /* Taux Badges */
    .taux-low {
        background: #fff;
        color: #1a1a1a;
        border: 1px solid #1a1a1a;
    }

    .taux-medium {
        background: #666;
        color: #fff;
    }

    .taux-complete {
        background: #1a1a1a;
        color: #fff;
    }

    .taux-exceeded {
        background: #333;
        color: #fff;
    }

    /* Hours Summary */
    .hours-summary {
        background: #f5f5f5;
        border-radius: 12px;
        padding: 20px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .summary-item i {
        font-size: 2rem;
        color: #1a1a1a;
    }

    .summary-item.complete i {
        color: #1a1a1a;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #666;
    }

    .global-taux .badge {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
</style>
@endsection
