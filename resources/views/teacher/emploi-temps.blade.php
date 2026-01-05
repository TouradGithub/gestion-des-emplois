@extends('layouts.teacher.master')

@section('title', __('teacher.my_schedule'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">{{ __('teacher.dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('teacher.my_schedule') }}</li>
@endsection

@section('content')
@php
    $printed_details = [];
@endphp

<style>
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #dee2e6;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .schedule-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 15px 10px;
        text-align: center;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .schedule-table td {
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: center;
        vertical-align: middle;
        height: 80px;
        min-width: 120px;
    }

    .schedule-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .schedule-table tbody tr:hover {
        background-color: #e9ecef;
    }

    .time-cell {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        font-weight: 600;
        color: #333;
        min-width: 80px;
    }

    .session-cell {
        background: rgba(102, 126, 234, 0.1);
        border-radius: 8px;
        padding: 10px !important;
    }

    .session-cell .subject-name {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .session-cell .class-name {
        color: #667eea;
        font-size: 13px;
        font-weight: 500;
    }

    .session-cell .room-name {
        color: #666;
        font-size: 12px;
        margin-top: 3px;
    }

    .page-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        color: #fff;
    }

    .page-header-card h3 {
        margin: 0;
        font-weight: 600;
    }

    .page-header-card p {
        margin: 5px 0 0;
        opacity: 0.8;
    }

    .empty-cell {
        background: #fafafa;
        height: 80px;
        min-width: 120px;
    }

    .session-cell {
        height: 80px;
        min-width: 120px;
    }

    .card-schedule {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-schedule .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 20px;
        border: none;
    }

    .card-schedule .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .btn-print {
        background: #fff;
        color: #667eea;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-print:hover {
        background: rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .schedule-table th {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .session-cell {
            background: rgba(102, 126, 234, 0.1) !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-card">
    <div>
        <h3><i class="mdi mdi-calendar-clock me-2"></i>{{ __('teacher.my_schedule') }}</h3>
        <p>{{ __('teacher.schedule_description') }}</p>
    </div>
</div>

<!-- Schedule Card -->
<div class="card card-schedule">
    <div class="card-header no-print">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="mdi mdi-table me-2"></i>{{ __('teacher.weekly_schedule') }}</h5>
            <span class="badge bg-light text-dark">
                <i class="mdi mdi-account me-1"></i>{{ $teacher->name }}
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        @if(count($calendarData) > 0)
        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="min-width: 100px;">{{ __('teacher.hours') }}</th>
                        @foreach($weekDays as $day)
                            <th>{{ $day->libelle_fr }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($calendarData as $time => $days)
                        <tr>
                            <td class="time-cell">{{ $time }}</td>
                            @foreach($days as $value)
                                @if(is_array($value) && !in_array($value['id'], $printed_details))
                                    <td rowspan="{{ $value['rowspan'] }}" class="session-cell">
                                        <div class="subject-name">
                                            <i class="mdi mdi-book-open-variant me-1"></i>
                                            {{ $value['matiere'] }}
                                        </div>
                                        <div class="class-name">
                                            <i class="mdi mdi-account-group me-1"></i>
                                            {{ $value['classe'] }}
                                        </div>
                                        @if($value['salle'])
                                        <div class="room-name">
                                            <i class="mdi mdi-door me-1"></i>
                                            {{ $value['salle'] }}
                                        </div>
                                        @endif
                                    </td>
                                    @php($printed_details[] = $value['id'])
                                @elseif($value === 1)
                                    <td class="empty-cell"></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="mdi mdi-calendar-blank display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">{{ __('teacher.no_schedule') }}</h5>
            <p class="text-muted">{{ __('teacher.no_schedule_description') }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Legend -->
<div class="card mt-4 no-print">
    <div class="card-body">
        <h6 class="mb-3"><i class="mdi mdi-information me-2"></i>{{ __('teacher.legend') }}</h6>
        <div class="d-flex flex-wrap gap-4">
            <div class="d-flex align-items-center">
                <div style="width: 20px; height: 20px; background: rgba(102, 126, 234, 0.1); border-radius: 4px; margin-left: 10px;"></div>
                <span>{{ __('teacher.scheduled_session') }}</span>
            </div>
            <div class="d-flex align-items-center">
                <div style="width: 20px; height: 20px; background: #fafafa; border: 1px solid #dee2e6; border-radius: 4px; margin-left: 10px;"></div>
                <span>{{ __('teacher.free_slot') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
