@extends('layouts.masters.master')

@section('title', __('messages.list_trimesters'))

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.list_trimesters') }}</h3>
        </div>

        <div class="mb-3 text-end">
            <a href="{{ route('web.trimesters.create') }}" class="btn btn-primary">{{ __('messages.add_trimester') }}</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Niveau pédagogique</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trimesters as $trimester)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $trimester->name }}</td>
                            <td>
{{--                                {{ $trimester->niveau->nom ($trimester->niveau->formation->nom ) }}--}}
                                {{ optional($trimester->niveau)->nom }} ({{ optional(optional($trimester->niveau)->formation)->nom }})

                                {{--                                {{ $trimester?->niveau->id  ($trimester->niveau)}}--}}

                            </td>
                            <td>
                                <a href="{{ route('web.trimesters.edit', $trimester) }}" class="btn btn-sm btn-warning">
                                    <i class="mdi mdi-pencil"></i> {{ __('messages.edit') }}
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-trimester"
                                        data-id="{{ $trimester->id }}"
                                        data-name="{{ $trimester->name }}">
                                    <i class="mdi mdi-delete"></i> {{ __('messages.delete') }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
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
    $('.delete-trimester').click(function() {
        const trimesterId = $(this).data('id');
        const trimesterName = $(this).data('name');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `{{ __('messages.confirm_delete_trimester') }} "${trimesterName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __('messages.yes_delete_exclamation') }}',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/trimesters/${trimesterId}`,
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
                            'Le trimestre a été supprimé avec succès.',
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
