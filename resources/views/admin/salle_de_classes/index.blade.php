
@extends('layouts.masters.master')

@section('title')
    {{ __('Salle de Classe') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des Salles de Classe</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        {{-- زر إضافة --}}
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.salle-de-classes.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('Ajouter une salle') }}
                                </a>
                            </div>
                        </div>

                        {{-- جدول البيانات --}}
                        <div class="row">
                            <div class="col-12">
                                <table class="table" id="table_list" data-toggle="table"
                                       data-url="{{ route('web.salle-de-classes.list') }}"
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
                                        <th data-field="code">Numéro de Salle</th>
                                        <th data-field="capacity">Nombre d'étudiants</th>
                                        <th data-field="formation">Formation</th>
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
                window.location.href = '/admin/salle-de-classes/' + row.id + '/edit';
            },
            'click .deletedata': function (e, value, row, index) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')) {
                    fetch('/admin/salle-de-classes/' + row.id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            $('#table_list').bootstrapTable('refresh');
                            alert(data.message);
                        })
                        .catch(error => {
                            alert('Erreur lors de la suppression');
                        });
                }
            }
        };
    </script>
@endsection
