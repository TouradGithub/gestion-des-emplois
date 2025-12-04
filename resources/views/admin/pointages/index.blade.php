@extends('layouts.masters.master')

@section('title')
    {{ __('pointages.pointages') }}
@endsection

@section('css')
<style>
    .page-header-custom {
        background: #1a1a1a;
        border-radius: 15px;
        padding: 25px 30px;
        margin-bottom: 25px;
        color: #fff;
    }
    .page-header-custom h3 {
        margin: 0;
        font-weight: 700;
    }
    .page-header-custom p {
        margin: 5px 0 0 0;
        opacity: 0.8;
    }
    .stats-card {
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 2px solid #e0e0e0;
        background: #fff;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #1a1a1a;
    }
    .stats-card .icon {
        font-size: 3rem;
        color: #1a1a1a;
        opacity: 0.7;
    }
    .stats-card .number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    .stats-card .label {
        font-size: 0.95rem;
        color: #666;
    }
    .stats-card.success-card {
        border-left: 4px solid #1a1a1a;
    }
    .stats-card.danger-card {
        border-left: 4px solid #666;
    }
    .stats-card.info-card {
        border-left: 4px solid #999;
    }
    .stats-card.warning-card {
        border-left: 4px solid #333;
    }
    .filter-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        border: 1px solid #e0e0e0;
    }
    .filter-card .form-label {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
    }
    .filter-card .form-control, .filter-card .form-select {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    .filter-card .form-control:focus, .filter-card .form-select:focus {
        border-color: #1a1a1a;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
    }
    .main-card {
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
    }
    .main-card .card-body {
        padding: 25px;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .table thead th {
        background: #1a1a1a;
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 15px;
        font-size: 0.9rem;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f5f5f5;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }
    .badge-present {
        background: #1a1a1a;
        color: #fff;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .badge-absent {
        background: #fff;
        color: #1a1a1a;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        border: 2px solid #1a1a1a;
    }
    .badge-classe {
        background: #f5f5f5;
        color: #1a1a1a;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        border: 1px solid #1a1a1a;
    }
    .badge-horaire {
        background: #fff;
        color: #1a1a1a;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        margin: 2px;
        display: inline-block;
        border: 1px solid #ccc;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.3s;
        border: 1px solid #1a1a1a;
        background: #fff;
        color: #1a1a1a;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        background: #1a1a1a;
        color: #fff;
    }
    .btn-action-delete {
        border-color: #666;
        color: #666;
    }
    .btn-action-delete:hover {
        background: #666;
        color: #fff;
    }
    .quick-actions .btn {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .quick-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .btn-add {
        background: #1a1a1a;
        color: #fff;
        border: none;
    }
    .btn-add:hover {
        background: #333;
        color: #fff;
    }
    .btn-rapide {
        background: #fff;
        color: #1a1a1a;
        border: 2px solid #1a1a1a;
    }
    .btn-rapide:hover {
        background: #1a1a1a;
        color: #fff;
    }
    .btn-calendar {
        background: #f5f5f5;
        color: #1a1a1a;
        border: 2px solid #666;
    }
    .btn-calendar:hover {
        background: #666;
        color: #fff;
    }
    .btn-filter {
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
    }
    .btn-filter:hover {
        background: #333;
        color: #fff;
    }
    .btn-reset {
        background: #fff;
        color: #1a1a1a;
        border: 2px solid #1a1a1a;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
    }
    .btn-reset:hover {
        background: #1a1a1a;
        color: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 80px;
        color: #ccc;
        margin-bottom: 20px;
    }
    .empty-state h5 {
        color: #666;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #999;
    }
    .teacher-info {
        display: flex;
        align-items: center;
    }
    .teacher-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        margin-right: 10px;
        font-size: 0.9rem;
    }
    .alert-custom {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
    }
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 3px;
        border: 1px solid #e0e0e0;
        color: #1a1a1a;
    }
    .pagination .page-item.active .page-link {
        background: #1a1a1a;
        border-color: #1a1a1a;
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3><i class="mdi mdi-clipboard-check me-2"></i> {{ __('pointages.liste_pointages') }}</h3>
                <p>Gestion et suivi des présences des enseignants</p>
            </div>
            <div class="quick-actions d-flex gap-2 flex-wrap">
                <a href="{{ route('web.pointages.create') }}" class="btn btn-add">
                    <i class="mdi mdi-plus-circle me-1"></i> Nouveau pointage
                </a>
                <a href="{{ route('web.pointages.rapide') }}" class="btn btn-rapide">
                    <i class="mdi mdi-clock-fast me-1"></i> Pointage rapide
                </a>
                <a href="{{ route('web.pointages.calendar') }}" class="btn btn-calendar">
                    <i class="mdi mdi-calendar-clock me-1"></i> Calendrier
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card success-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">{{ $stats['presents'] ?? 0 }}</div>
                        <div class="label">Présents</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card danger-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">{{ $stats['absents'] ?? 0 }}</div>
                        <div class="label">Absents</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-close-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">{{ $stats['total'] ?? 0 }}</div>
                        <div class="label">Total pointages</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-clipboard-list"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">
                            @php
                                $tauxPresence = ($stats['total'] ?? 0) > 0
                                    ? round((($stats['presents'] ?? 0) / $stats['total']) * 100, 1)
                                    : 0;
                            @endphp
                            {{ $tauxPresence }}%
                        </div>
                        <div class="label">Taux de présence</div>
                    </div>
                    <div class="icon">
                        <i class="mdi mdi-percent"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('web.pointages.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="mdi mdi-account-tie me-1"></i> Enseignant</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">Tous les enseignants</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="mdi mdi-calendar me-1"></i> Date début</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="mdi mdi-calendar me-1"></i> Date fin</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="mdi mdi-tag me-1"></i> Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="present" {{ request('statut') == 'present' ? 'selected' : '' }}>Présent</option>
                            <option value="absent" {{ request('statut') == 'absent' ? 'selected' : '' }}>Absent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter flex-grow-1">
                            <i class="mdi mdi-filter me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('web.pointages.index') }}" class="btn btn-reset">
                            <i class="mdi mdi-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="card main-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Enseignant</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Horaires</th>
                            <th>Statut</th>
                            <th>Heure arrivée</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pointages as $pointage)
                            <tr>
                                <td>
                                    <strong>{{ $pointage->date_pointage->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $pointage->date_pointage->translatedFormat('l') }}</small>
                                </td>
                                <td>
                                    <div class="teacher-info">
                                        <div class="teacher-avatar">
                                            {{ strtoupper(substr($pointage->teacher->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $pointage->teacher->name ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pointage->emploiTemps->subject->name ?? '-' }}</td>
                                <td>
                                    <span class="badge-classe">
                                        {{ $pointage->emploiTemps->classe->nom ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($pointage->emploiTemps && $pointage->emploiTemps->horairess->count() > 0)
                                        @foreach($pointage->emploiTemps->horairess as $horaire)
                                            <span class="badge-horaire">{{ $horaire->libelle_fr }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pointage->statut == 'present')
                                        <span class="badge-present">
                                            <i class="mdi mdi-check me-1"></i> Présent
                                        </span>
                                    @else
                                        <span class="badge-absent">
                                            <i class="mdi mdi-close me-1"></i> Absent
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($pointage->heure_arrivee)
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ $pointage->heure_arrivee->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('web.pointages.show', $pointage->id) }}"
                                       class="btn-action" title="Voir">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="{{ route('web.pointages.edit', $pointage->id) }}"
                                       class="btn-action" title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('web.pointages.destroy', $pointage->id) }}"
                                          style="display: inline;"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce pointage ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Supprimer">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="mdi mdi-clipboard-text-clock"></i>
                                        <h5>Aucun pointage trouvé</h5>
                                        <p>Commencez par créer un nouveau pointage ou modifiez vos filtres</p>
                                        <a href="{{ route('web.pointages.create') }}" class="btn btn-add mt-3">
                                            <i class="mdi mdi-plus-circle me-1"></i> Créer un pointage
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pointages->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $pointages->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
