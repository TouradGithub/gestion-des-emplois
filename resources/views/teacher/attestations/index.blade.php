@extends('layouts.teacher.master')

@section('title', 'Mes Attestations')

@section('breadcrumb')
<li class="breadcrumb-item active">Attestations</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="mdi mdi-certificate me-2"></i> Mes Attestations</h4>
            <a href="{{ route('teacher.attestations.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle me-1"></i> Nouvelle demande
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3>{{ $attestations->where('status', 'pending')->count() }}</h3>
                <p class="mb-0">En attente</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $attestations->where('status', 'approved')->count() }}</h3>
                <p class="mb-0">Approuvées</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $attestations->where('status', 'rejected')->count() }}</h3>
                <p class="mb-0">Rejetées</p>
            </div>
        </div>
    </div>
</div>

<!-- Attestations List -->
<div class="card">
    <div class="card-body">
        @if($attestations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date de demande</th>
                            <th>Type</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attestations as $attestation)
                        <tr>
                            <td>{{ $attestation->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $attestation->type_label }}</td>
                            <td>{{ $attestation->motif ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $attestation->status_badge_class }}">
                                    {{ $attestation->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($attestation->status === 'approved')
                                    <a href="{{ route('teacher.attestations.download', $attestation) }}"
                                       class="btn btn-sm btn-success" target="_blank">
                                        <i class="mdi mdi-download"></i> Télécharger
                                    </a>
                                @elseif($attestation->status === 'pending')
                                    <form action="{{ route('teacher.attestations.destroy', $attestation) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="mdi mdi-close"></i> Annuler
                                        </button>
                                    </form>
                                @elseif($attestation->status === 'rejected')
                                    <button type="button" class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="tooltip"
                                            title="{{ $attestation->rejection_reason }}">
                                        <i class="mdi mdi-information"></i> Motif
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="mdi mdi-certificate-outline display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Aucune attestation</h5>
                <p class="text-muted">Vous n'avez pas encore demandé d'attestation.</p>
                <a href="{{ route('teacher.attestations.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus-circle me-1"></i> Faire une demande
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endsection
