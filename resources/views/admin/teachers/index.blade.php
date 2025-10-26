@extends('layouts.masters.master')

@section('title')
    {{ trans('teacher.teachers') }}
@endsection

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.list_teachers') }}</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{-- زر إضافة أستاذ جديد --}}
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.teachers.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('Ajouter un enseignant') }}
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class='table' id='table_list' data-toggle="table"
                                       data-url="{{ route('web.teachers.list') }}"
                                       data-side-pagination="server" data-pagination="true"
                                       data-page-list="[5, 10, 20, 50, 100]"
                                       data-search="true" data-show-refresh="true"
                                       data-show-columns="true" data-sort-name="id" data-sort-order="desc"
                                       data-query-params="queryParams">
                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">#</th>
                                        <th data-field="no">No</th>
                                        <th data-field="name">Nom</th>
                                        <th data-field="email">Email</th>
                                        <th data-field="phone">Tel</th>
                                        <th data-field="nni">Tel</th>
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
                window.location.href = '/admin/teachers/' + row.id + '/edit';
            },
            'click .delete-teacher': function (e, value, row, index) {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: '{{ __('messages.confirm_delete_teacher') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ url("/admin/teachers") }}/' + row.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            $('#table_list').bootstrapTable('refresh');
                            Swal.fire('Supprimé!', data.message || 'Enseignant supprimé avec succès', 'success');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Erreur!', 'Erreur lors de la suppression', 'error');
                        });
                    }
                });
            }
        };
    </script>
@endsection
