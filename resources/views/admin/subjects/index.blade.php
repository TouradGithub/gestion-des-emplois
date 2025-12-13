@extends('layouts.masters.master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }
    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">
            <i class="fas fa-book"></i> Liste des Matières
        </h4>
        <a href="{{ route('web.subjects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une Matière
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Spécialité</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($subjects as $index => $subject)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $subject->name }}</strong></td>
                        <td><code>{{ $subject->code }}</code></td>
                        <td>
                            @if($subject->subjectType)
                                {{ $subject->subjectType->name }}
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td>
                            @if($subject->specialite)
                                {{ $subject->specialite->name }}
                                @if($subject->specialite->niveau)
                                    <small class="text-muted">({{ $subject->specialite->niveau->nom }})</small>
                                @endif
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('web.subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-subject"
                                    data-id="{{ $subject->id }}"
                                    data-name="{{ $subject->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune matière trouvée</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.delete-subject').click(function() {
        const subjectId = $(this).data('id');
        const subjectName = $(this).data('name');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `Voulez-vous vraiment supprimer la matière "${subjectName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/subjects/${subjectId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove the row with animation
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                        
                        Swal.fire(
                            'Supprimé!',
                            'La matière a été supprimée avec succès.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue lors de la suppression.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endsection
