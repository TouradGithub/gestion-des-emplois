@extends('layouts.teacher.master')

@section('title', 'Mes Demandes')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="mdi mdi-clipboard-list me-2"></i>
                    Demandes d'ajout de séances
                </h4>
                <a href="{{ route('teacher.requests.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus me-1"></i>
                    Nouvelle demande
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
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
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $request->id }}" title="Supprimer">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        @endif

                                        @if($request->admin_note)
                                            <button class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ $request->admin_note }}">
                                                <i class="mdi mdi-comment-text"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="mdi mdi-clipboard-text-off display-4 text-muted d-block mb-2"></i>
                                        <span class="text-muted">Aucune demande</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Delete request
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: 'Cette demande sera supprimée définitivement',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("teacher/requests") }}/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé!', response.message, 'success').then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Erreur!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        var message = 'Une erreur est survenue';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire('Erreur!', message, 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
