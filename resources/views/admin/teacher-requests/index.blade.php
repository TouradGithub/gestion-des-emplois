@extends('layouts.masters.master')

@section('title', 'Demandes des enseignants')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <i class="mdi mdi-clipboard-list mr-2"></i>
            Demandes d'ajout de séances
        </h3>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filtres -->
                    <form method="GET" class="row mb-4">
                        <div class="col-md-3 mb-2">
                            <select name="status" class="form-control">
                                <option value="">-- Tous les statuts --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="teacher_id" class="form-control">
                                <option value="">-- Tous les enseignants --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name ?? $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="mdi mdi-filter"></i> Filtrer
                            </button>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('web.teacher-requests.index') }}" class="btn btn-secondary btn-block">
                                <i class="mdi mdi-refresh"></i> Réinitialiser
                            </a>
                        </div>
                    </form>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-0">{{ $requests->where('status', 'pending')->count() }}</h4>
                                    <small>En attente</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-0">{{ $requests->where('status', 'approved')->count() }}</h4>
                                    <small>Approuvé</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-0">{{ $requests->where('status', 'rejected')->count() }}</h4>
                                    <small>Refusé</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Enseignant</th>
                                    <th>Matière</th>
                                    <th>Classe</th>
                                    <th>Jour</th>
                                    <th>Horaire</th>
                                    <th>Salle</th>
                                    <th>Statut</th>
                                    <th>Date demande</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $index => $request)
                                    <tr>
                                        <td>{{ $requests->firstItem() + $index }}</td>
                                        <td>{{ $request->teacher->user->name ?? $request->teacher->name ?? '-' }}</td>
                                        <td>{{ $request->subject->name ?? '-' }}</td>
                                        <td>{{ $request->classe->nom ?? '-' }}</td>
                                        <td>{{ $request->jour->libelle_fr ?? '-' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($request->horaire->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($request->horaire->end_time)->format('H:i') }}
                                        </td>
                                        <td>{{ $request->salle->name ?? '-' }}</td>
                                        <td>{!! $request->status_badge !!}</td>
                                        <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($request->isPending())
                                                <button class="btn btn-sm btn-success btn-approve" data-id="{{ $request->id }}" title="Approuver">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-reject" data-id="{{ $request->id }}" title="Refuser">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            @else
                                                @if($request->approvedBy)
                                                    <small class="text-muted">
                                                        Par: {{ $request->approvedBy->name }}
                                                    </small>
                                                @endif
                                            @endif

                                            @if($request->note)
                                                <button class="btn btn-sm btn-info" data-toggle="tooltip" title="Note de l'enseignant: {{ $request->note }}">
                                                    <i class="mdi mdi-comment"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="mdi mdi-clipboard-text-off display-4 text-muted d-block mb-2"></i>
                                            <span class="text-muted">Aucune demande</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $requests->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour la note admin (Bootstrap 4) -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noteModalTitle">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="adminNote">Note pour l'enseignant (optionnel)</label>
                    <textarea id="adminNote" class="form-control" rows="3" placeholder="Ajouter une note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var currentRequestId = null;
    var currentAction = null;

    // Initialiser les tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Approuver la demande
    $('.btn-approve').on('click', function() {
        currentRequestId = $(this).data('id');
        currentAction = 'approve';
        $('#noteModalTitle').text('Approuver la demande');
        $('#confirmAction').removeClass('btn-danger').addClass('btn-success').text('Approuver');
        $('#noteModal').modal('show');
    });

    // Refuser la demande
    $('.btn-reject').on('click', function() {
        currentRequestId = $(this).data('id');
        currentAction = 'reject';
        $('#noteModalTitle').text('Refuser la demande');
        $('#confirmAction').removeClass('btn-success').addClass('btn-danger').text('Refuser');
        $('#noteModal').modal('show');
    });

    // Confirmer l'action
    $('#confirmAction').on('click', function() {
        var url = '{{ url("admin/teacher-requests") }}/' + currentRequestId + '/' + currentAction;
        var adminNote = $('#adminNote').val();

        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { admin_note: adminNote },
            success: function(response) {
                $('#noteModal').modal('hide');
                if (response.success) {
                    Swal.fire('Succès!', response.message, 'success').then(function() {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
            },
            error: function(xhr) {
                $('#noteModal').modal('hide');
                var message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Erreur!', message, 'error');
            }
        });
    });

    // Vider la note quand le modal est fermé
    $('#noteModal').on('hidden.bs.modal', function() {
        $('#adminNote').val('');
        currentRequestId = null;
        currentAction = null;
    });
});
</script>
@endsection
