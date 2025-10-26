@extends('layouts.masters.master')

@section('title')
    Liste des Niveaux Pédagogiques
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des Niveaux Pédagogiques</h3>
            <a href="{{ route('web.niveau-pedagogiques.create') }}" class="btn btn-success btn-sm">Ajouter</a>
        </div>

        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Ordre</th>
                        <th>Formation</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($niveaux as $niveau)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $niveau->nom }}</td>
                            <td>{{ $niveau->ordre }}</td>
                            <td>{{ $niveau->formation->nom ?? '-' }}</td>
                            <td>
                                <a href="{{ route('web.niveau-pedagogiques.edit', $niveau->id) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pencil"></i> Modifier
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-niveau"
                                        data-id="{{ $niveau->id }}"
                                        data-name="{{ $niveau->nom }}">
                                    <i class="mdi mdi-delete"></i> Supprimer
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if($niveaux->isEmpty())
                        <tr><td colspan="5" class="text-center">Aucun niveau disponible.</td></tr>
                    @endif
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
    $('.delete-niveau').click(function() {
        const niveauId = $(this).data('id');
        const niveauName = $(this).data('name');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `Voulez-vous vraiment supprimer le niveau "${niveauName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/niveau-pedagogiques/${niveauId}`,
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
                            'Le niveau pédagogique a été supprimé avec succès.',
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
