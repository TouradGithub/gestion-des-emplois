@extends('layouts.masters.master')

@section('title', __('messages.list_classes'))

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-school"></i>
                </span>
                Classes
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Classes</li>
                </ul>
            </nav>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('web.classes.create') }}" class="btn btn-gradient-primary">
                <i class="mdi mdi-plus"></i> {{ __('messages.add_class') }}
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                        <tr>
                            <th><i class="mdi mdi-account-group"></i> Nom</th>
                            <th><i class="mdi mdi-signal-variant"></i> Niveau</th>
                            <th><i class="mdi mdi-book-open-variant"></i> Spécialité</th>
                            <th><i class="mdi mdi-calendar"></i> Année</th>
                            <th><i class="mdi mdi-cog"></i> Actions</th>
                        </tr>
                        </thead>
            <tbody>
            @foreach ($classes as $classe)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-account-group me-2 text-primary"></i>
                            <strong>{{ $classe->nom }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-gradient-info">{{ $classe->niveau->nom }}</span>
                    </td>
                    <td>{{ $classe->specialite->name }}</td>
                    <td>
                        <i class="mdi mdi-calendar me-1"></i>{{ $classe->annee->annee }}
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            @if($classe->emplois)
                                <a href="{{ route('web.emplois.showEmploi', $classe->id) }}" class="btn btn-info btn-sm">
                                    <i class="mdi mdi-calendar-clock"></i> Emploi
                                </a>
                            @endif

                            <a href="{{ route('web.classes.edit', $classe->id) }}" class="btn btn-primary btn-sm">
                                <i class="mdi mdi-pencil"></i> {{ __('messages.edit') }}
                            </a>

                            <button type="button" class="btn btn-danger btn-sm delete-class"
                                    data-id="{{ $classe->id }}"
                                    data-name="{{ $classe->nom }}">
                                <i class="mdi mdi-delete"></i> {{ __('messages.delete') }}
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.delete-class').click(function() {
        const classId = $(this).data('id');
        const className = $(this).data('name');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `{{ __('messages.confirm_delete_class') }} "${className}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __('messages.yes_delete_exclamation') }}',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/classes/${classId}`,
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
                            'La classe a été supprimée avec succès.',
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
