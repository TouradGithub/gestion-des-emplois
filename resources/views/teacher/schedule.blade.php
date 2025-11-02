@extends('layouts.teacher.master')

@section('title', __('teacher.schedule_for_subject', ['subject' => $subjectTeacher->subject->name ?? 'N/A']))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('teacher.dashboard') }}">{{ __('teacher.dashboard') }}</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('teacher.departments') }}">{{ __('teacher.my_departments') }}</a>
</li>
<li class="breadcrumb-item active">{{ $subjectTeacher->subject->name ?? 'N/A' }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Subject Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2">
                            <i class="mdi mdi-book-open-variant text-primary me-2"></i>
                            {{ $subjectTeacher->subject->name ?? 'N/A' }}
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">
                                    <i class="mdi mdi-tag-outline me-1"></i>
                                    <strong>{{ __('teacher.subject_code') }}:</strong> {{ $subjectTeacher->subject->code ?? 'N/A' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="mdi mdi-domain me-1"></i>
                                    <strong>{{ __('teacher.department') }}:</strong> {{ $subjectTeacher->subject->specialite->departement->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">
                                    <i class="mdi mdi-school me-1"></i>
                                    <strong>{{ __('teacher.speciality') }}:</strong> {{ $subjectTeacher->subject->specialite->name ?? 'N/A' }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="mdi mdi-google-classroom me-1"></i>
                                    <strong>{{ __('teacher.class') }}:</strong> {{ $subjectTeacher->classe->nom ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('teacher.departments') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-calendar-clock me-2"></i>
                    {{ __('teacher.schedule_title') }}
                </h5>
                <small class="text-muted">{{ __('teacher.schedule_description') }}</small>
            </div>
            <div class="card-body">
                @if(count($schedules) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered schedule-table">
                            <thead class="table-dark">
                                <tr>
                                    <th width="15%">
                                        <i class="mdi mdi-calendar me-1"></i>
                                        {{ __('teacher.day') }}
                                    </th>
                                    <th width="15%">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ __('teacher.time') }}
                                    </th>
                                    <th width="25%">
                                        <i class="mdi mdi-book-open-variant me-1"></i>
                                        {{ __('teacher.subject') }}
                                    </th>
                                    <th width="20%">
                                        <i class="mdi mdi-google-classroom me-1"></i>
                                        {{ __('teacher.classroom') }}
                                    </th>
                                    <th width="25%">
                                        <i class="mdi mdi-school me-1"></i>
                                        {{ __('messages.class') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $groupedSchedules = $schedules->groupBy('jour.name');
                                    $dayColors = [
                                        'الإثنين' => 'success',
                                        'الثلاثاء' => 'primary',
                                        'الأربعاء' => 'warning',
                                        'الخميس' => 'info',
                                        'الجمعة' => 'danger',
                                        'السبت' => 'secondary'
                                    ];
                                @endphp

                                @foreach($groupedSchedules as $dayName => $daySchedules)
                                    @foreach($daySchedules as $index => $schedule)
                                    <tr class="schedule-row">
                                        @if($index === 0)
                                            <td rowspan="{{ count($daySchedules) }}" class="day-cell align-middle">
                                                <div class="day-badge">
                                                    <span class="badge bg-{{ $dayColors[$dayName] ?? 'primary' }} p-2">
                                                        <i class="mdi mdi-calendar-today me-1"></i>
                                                        {{ __('teacher.' . strtolower(str_replace(['الإ', 'اء', 'عاء', 'يس', 'عة', 'بت'], ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], $dayName))) }}
                                                    </span>
                                                </div>
                                            </td>
                                        @endif

                                        <td class="time-cell">
                                            <div class="time-info">
                                                <i class="mdi mdi-clock text-primary me-1"></i>
                                                <strong>{{ $schedule->horaire->heure_debut ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $schedule->horaire->heure_fin ?? '-' }}</small>
                                            </div>
                                        </td>

                                        <td class="subject-cell">
                                            <div class="subject-info">
                                                <strong class="text-primary">{{ $schedule->subject->name ?? '-' }}</strong>
                                                @if($schedule->subject->code)
                                                    <br><small class="text-muted">{{ $schedule->subject->code }}</small>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="classroom-cell">
                                            <div class="classroom-info">
                                                @if($schedule->salle)
                                                    <i class="mdi mdi-door text-info me-1"></i>
                                                    <strong>{{ $schedule->salle->name ?? '-' }}</strong>
                                                    @if($schedule->salle->code)
                                                        <br><small class="text-muted">{{ $schedule->salle->code }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="class-cell">
                                            <div class="class-info">
                                                <strong>{{ $schedule->classe->name ?? '-' }}</strong>
                                                @if($schedule->classe->specialite)
                                                    <br><small class="text-muted">{{ $schedule->classe->specialite->name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Schedule Summary -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-information-outline me-2"></i>
                                    <div>
                                        <strong>{{ __('messages.summary') }}:</strong>
                                        {{ __('teacher.total_classes') }}: {{ count($schedules) }} |
                                        {{ __('teacher.days_count') }}: {{ count($groupedSchedules) }} |
                                        {{ __('teacher.subject') }}: {{ $subjectTeacher->subject->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="mdi mdi-calendar-remove display-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted">{{ __('teacher.no_schedule') }}</h4>
                        <p class="text-muted mb-4">{{ __('teacher.no_schedule_message') }}</p>

                        <div class="alert alert-warning d-inline-block">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline me-2"></i>
                                <div class="text-start">
                                    <strong>{{ __('teacher.contact_admin') }}</strong><br>
                                    <small>{{ __('teacher.schedule_contact_message') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('teacher.departments') }}" class="btn btn-outline-primary">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                {{ __('teacher.back_to_departments') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .schedule-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .schedule-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .schedule-row:hover {
        background-color: #f8f9fa;
    }

    .day-cell {
        background-color: #f8f9fa;
        border-right: 4px solid #007bff;
    }

    .day-badge {
        text-align: center;
    }

    .day-badge .badge {
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .time-cell {
        text-align: center;
        vertical-align: middle;
    }

    .time-info strong {
        color: #2c3e50;
        font-size: 1rem;
    }

    .subject-cell, .classroom-cell, .class-cell {
        vertical-align: middle;
    }

    .subject-info strong {
        font-size: 1rem;
        color: #2c3e50;
    }

    .classroom-info, .class-info {
        line-height: 1.4;
    }

    .display-1 {
        font-size: 4rem;
        opacity: 0.3;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    @media (max-width: 768px) {
        .schedule-table {
            font-size: 0.85rem;
        }

        .day-badge .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .time-info strong {
            font-size: 0.9rem;
        }
    }
</style>
@endsection
