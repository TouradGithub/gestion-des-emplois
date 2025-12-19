@extends('layouts.teacher.master')

@section('title', __('teacher.my_pointages'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('teacher.my_pointages') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-clock-check me-2"></i>
                    {{ __('teacher.my_pointages') }}
                </h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-info">
                        <i class="mdi mdi-eye"></i>
                        {{ __('teacher.read_only_access') }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <form method="GET" action="{{ route('teacher.pointages') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('teacher.from_date') }}</label>
                                <input type="date" name="from_date" class="form-control" 
                                       value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('teacher.to_date') }}</label>
                                <input type="date" name="to_date" class="form-control" 
                                       value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i>
                                    {{ __('teacher.filter') }}
                                </button>
                                <a href="{{ route('teacher.pointages') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-refresh"></i>
                                    {{ __('teacher.reset') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Pointages Table -->
                @if($pointages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('teacher.pointage_date') }}</th>
                                    <th>{{ __('teacher.status') }}</th>
                                    <th>{{ __('messages.class') }}</th>
                                    <th>{{ __('messages.subject') }}</th>
                                    <th>{{ __('teacher.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pointages as $pointage)
                                <tr>
                                    <td>
                                        <i class="mdi mdi-calendar me-1"></i>
                                        {{ Carbon\Carbon::parse($pointage->date_pointage)->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        @if($pointage->statut == 'present')
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check"></i>
                                                Présent
                                            </span>
                                        @elseif($pointage->statut == 'absent')
                                            <span class="badge bg-danger">
                                                <i class="mdi mdi-close"></i>
                                                Absent
                                            </span>
                                        @elseif($pointage->statut == 'late' || $pointage->statut == 'retard')
                                            <span class="badge bg-warning">
                                                <i class="mdi mdi-clock-alert"></i>
                                                En retard
                                            </span>
                                        @elseif($pointage->statut == 'excuse')
                                            <span class="badge bg-info">
                                                <i class="mdi mdi-account-check"></i>
                                                Excusé
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ $pointage->statut }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pointage->emploiTemps && $pointage->emploiTemps->classe)
                                            <i class="mdi mdi-account-group me-1"></i>
                                            {{ $pointage->emploiTemps->classe->nom }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pointage->emploiTemps && $pointage->emploiTemps->subject)
                                            <i class="mdi mdi-book me-1"></i>
                                            {{ $pointage->emploiTemps->subject->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pointage->notes)
                                            <span class="text-muted">{{ $pointage->notes }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $pointages->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-clock-outline display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">{{ __('teacher.no_pointages') }}</h4>
                        <p class="text-muted">{{ __('teacher.no_data_available') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Info Alert -->
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="mdi mdi-information me-2"></i>
            <strong>{{ __('messages.note') }}:</strong>
            {{ __('teacher.view_only') }} - {{ __('teacher.cannot_edit') }} {{ __('teacher.cannot_delete') }}
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .badge {
        font-size: 0.875em;
    }
    
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
</style>
@endsection