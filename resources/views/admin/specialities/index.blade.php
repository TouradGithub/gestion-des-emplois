@extends('layouts.masters.master')

@section('title')
    {{ __('sidebar.specialities') }}
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.list_specialities') }}</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.specialities.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('messages.add_speciality') }}
                                </a>
                            </div>
                        </div>

                        <table class="table" id="table_list" data-toggle="table"
                               data-url="{{ route('web.specialities.list') }}"
                               data-side-pagination="server" data-pagination="true"
                               data-page-list="[5,10,20,50,100]"
                               data-search="true" data-show-refresh="true"
                               data-show-columns="true" data-sort-name="id" data-sort-order="desc"
                               data-query-params="queryParams">
                            <thead>
                            <tr>
                                <th data-field="id" data-visible="false">#</th>
                                <th data-field="no">No</th>
                                <th data-field="name">Nom</th>
                                <th data-field="code">Code</th>
                                <th data-field="departement">Département</th>
                                <th data-field="niveau">Niveau</th>
                                <th data-field="operate" data-events="actionEvents" data-sortable="false">Actions</th>
                            </tr>
                            </thead>
                        </table>

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
                window.location.href = '/admin/specialities/' + row.id + '/edit';
            },
            'click .deletedata': function (e, value, row, index) {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: 'Voulez-vous vraiment supprimer cette spécialité?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(e.target).closest('a').data('url'),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Supprimé!', 'La spécialité a été supprimée.', 'success');
                                $('#table_list').bootstrapTable('refresh');
                            },
                            error: function(xhr) {
                                Swal.fire('Erreur!', 'Une erreur est survenue lors de la suppression.', 'error');
                            }
                        });
                    }
                });
            }
        };
    </script>
@endsection
