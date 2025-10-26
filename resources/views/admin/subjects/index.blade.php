@extends('layouts.masters.master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <h2>Subjects</h2>
    <a href="{{ route('web.subjects.create') }}" class="btn btn-primary mb-3">Add Subject</a>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Specialité</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->code }}</td>
                <td>{{ $subject->specialite?->name  .'  ( '.$subject->specialite?->niveau?->nom.' ) ' }}</td>
                <td>
                    <a href="{{ route('web.subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                        <i class="mdi mdi-pencil"></i> Modifier
                    </a>
                    <button type="button" class="btn btn-sm btn-danger delete-subject"
                            data-id="{{ $subject->id }}"
                            data-name="{{ $subject->name }}">
                        <i class="mdi mdi-delete"></i> Supprimer
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
