@extends('layouts.masters.master')

@section('title')
    {{ __('Années scolaires') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des années scolaires</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.anneescolaires.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> {{ __('Ajouter une année scolaire') }}
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Année</th>
                                        <th>Date début</th>
                                        <th>Date fin</th>
                                        <th>Classes</th>
                                        <th>Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anneescolaires as $index => $annee)
                                        <tr>
                                            <td>{{ $anneescolaires->firstItem() + $index }}</td>
                                            <td>
                                                <a href="{{ route('web.anneescolaires.details', $annee->id) }}" class="text-primary fw-bold">
                                                    {{ $annee->annee }}
                                                </a>
                                            </td>
                                            <td>{{ $annee->date_debut }}</td>
                                            <td>{{ $annee->date_fin }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $annee->classes_count }} classe(s)</span>
                                            </td>
                                            <td>
                                                @if($annee->is_active)
                                                    <span class="badge bg-success">Oui</span>
                                                @else
                                                    <span class="badge bg-danger">Non</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-gradient-info me-1" href="{{ route('web.anneescolaires.details', $annee->id) }}" title="Voir">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-xs btn-gradient-primary me-1" href="{{ route('web.anneescolaires.edit', $annee->id) }}" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button class="btn btn-xs btn-gradient-danger btn-delete" data-id="{{ $annee->id }}" data-classes="{{ $annee->classes_count }}" title="Supprimer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fa fa-calendar-times fa-3x mb-3 d-block"></i>
                                                Aucune année scolaire trouvée
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $anneescolaires->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Delete button click
            $('.btn-delete').on('click', function() {
                var id = $(this).data('id');
                var classesCount = $(this).data('classes');

                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: 'Voulez-vous vraiment supprimer cette année scolaire?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("admin/anneescolaires") }}/' + id,
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
