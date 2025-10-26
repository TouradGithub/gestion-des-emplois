@extends('layouts.masters.master')

@section('title')
    {{ trans('niveauformation.niveauformations') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des niveaux de formation</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{-- زر إضافة جديد --}}
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.niveauformations.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> {{ __('Ajouter un niveau') }}
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class='table' id='table_list' data-toggle="table"
                                       data-url="{{ route('web.niveauformations.list') }}"
                                       data-side-pagination="server" data-pagination="true"
                                       data-page-list="[5, 10, 20, 50, 100]"
                                       data-search="true" data-show-refresh="true"
                                       data-show-columns="true" data-sort-name="id" data-sort-order="desc"
                                       data-query-params="queryParams">
                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">#</th>
                                        <th data-field="no">No</th>
                                        <th data-field="nom">Nom</th>
                                        <th data-field="ordre">Ordre</th>
                                        <th data-field="operate" data-events="actionEvents" data-sortable="false">Actions</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function queryParams(p) {
            return {
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search
            };
        }

        window.actionEvents = {
            'click .editdata': function (e, value, row, index) {
                window.location.href = '/admin/niveauformations/' + row.id + '/edit';
            },
            'click .deletedata': function (e, value, row, index) {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: 'Voulez-vous vraiment supprimer ce niveau de formation?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ url("/admin/niveauformations") }}/' + row.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(response => {
                            $('#table_list').bootstrapTable('refresh');
                            Swal.fire('Supprimé!', response.message || 'Supprimé avec succès', 'success');
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Erreur!', 'Une erreur est survenue', 'error');
                        });
                    }
                });
            }
        };
    </script>
@endsection
