@extends('layouts.masters.master')

@section('title', 'Gestion des Attestations')

@section('css')
<style>
    .stats-card {
        border-radius: 15px;
        padding: 20px;
        transition: transform 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .badge-type {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-certificate"></i>
            </span>
            Gestion des Attestations
        </h3>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-warning">
                <div class="card-body text-center text-dark">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p class="mb-0">En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success">
                <div class="card-body text-center text-white">
                    <h3>{{ $stats['approved'] }}</h3>
                    <p class="mb-0">Approuvées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-danger">
                <div class="card-body text-center text-white">
                    <h3>{{ $stats['rejected'] }}</h3>
                    <p class="mb-0">Rejetées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info">
                <div class="card-body text-center text-white">
                    <h3>{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('web.attestations.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Enseignant</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="travail" {{ request('type') == 'travail' ? 'selected' : '' }}>Attestation de travail</option>
                        <option value="salaire" {{ request('type') == 'salaire' ? 'selected' : '' }}>Attestation de salaire</option>
                        <option value="experience" {{ request('type') == 'experience' ? 'selected' : '' }}>Attestation d'expérience</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvées</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="mdi mdi-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('web.attestations.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attestations Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Enseignant</th>
                            <th>Type</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attestations as $attestation)
                        <tr>
                            <td>{{ $attestation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $attestation->teacher->name ?? '-' }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-type bg-secondary">{{ $attestation->type_label }}</span>
                            </td>
                            <td>{{ Str::limit($attestation->motif, 50) ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $attestation->status_badge_class }}">
                                    {{ $attestation->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($attestation->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success btn-approve"
                                            data-id="{{ $attestation->id }}"
                                            data-teacher="{{ $attestation->teacher->name ?? '' }}">
                                        <i class="mdi mdi-check"></i> Approuver
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject"
                                            data-id="{{ $attestation->id }}"
                                            data-teacher="{{ $attestation->teacher->name ?? '' }}">
                                        <i class="mdi mdi-close"></i> Rejeter
                                    </button>
                                @elseif($attestation->status === 'approved')
                                    <a href="{{ route('web.attestations.download', $attestation) }}"
                                       class="btn btn-sm btn-info" target="_blank">
                                        <i class="mdi mdi-download"></i> PDF
                                    </a>
                                    <small class="text-muted ms-2">{{ $attestation->attestation_number }}</small>
                                @else
                                    <span class="text-muted" title="{{ $attestation->rejection_reason }}">
                                        <i class="mdi mdi-information-outline"></i> Voir motif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="mdi mdi-certificate-outline mdi-48px text-muted"></i>
                                <p class="text-muted mt-2">Aucune demande d'attestation trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $attestations->links() }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter la demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Enseignant: <strong id="rejectTeacherName"></strong></p>
                <div class="mb-3">
                    <label class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                    <textarea id="rejectionReason" class="form-control" rows="3"
                              placeholder="Veuillez indiquer le motif du rejet..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Rejeter</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    let currentAttestationId = null;

    // Approve
    $('.btn-approve').click(function() {
        const id = $(this).data('id');
        const teacher = $(this).data('teacher');

        if (confirm('Voulez-vous approuver cette demande de ' + teacher + ' ?')) {
            $.ajax({
                url: '/admin/attestations/' + id + '/approve',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Attestation approuvée avec succès!\nNuméro: ' + response.attestation_number);
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Erreur: ' + (xhr.responseJSON?.message || 'Une erreur est survenue'));
                }
            });
        }
    });

    // Reject - Show Modal
    $('.btn-reject').click(function() {
        currentAttestationId = $(this).data('id');
        $('#rejectTeacherName').text($(this).data('teacher'));
        $('#rejectionReason').val('');
        $('#rejectModal').modal('show');
    });

    // Confirm Reject
    $('#confirmReject').click(function() {
        const reason = $('#rejectionReason').val().trim();
        if (!reason) {
            alert('Veuillez indiquer le motif du rejet.');
            return;
        }

        $.ajax({
            url: '/admin/attestations/' + currentAttestationId + '/reject',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { rejection_reason: reason },
            success: function(response) {
                if (response.success) {
                    $('#rejectModal').modal('hide');
                    alert('Demande rejetée.');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Erreur: ' + (xhr.responseJSON?.message || 'Une erreur est survenue'));
            }
        });
    });
});
</script>
@endsection
