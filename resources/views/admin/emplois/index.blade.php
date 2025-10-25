@extends('layouts.masters.master')

@section('title')
    {{ __('Emplois du Temps') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Liste des emplois du temps</h3>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{-- Bouton d'ajout --}}
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('web.emplois.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> {{ __('Ajouter un emploi') }}
                                </a>
                            </div>
                        </div>

                        {{-- Tableau dynamique --}}
                        <div class="row">
                            <div class="col-12">
                                <table class="table" id="table_list"
                                       data-toggle="table"
                                       data-url="{{ route('web.emplois.list') }}"
                                       data-side-pagination="server"
                                       data-pagination="true"
                                       data-page-list="[5, 10, 20, 50, 100]"
                                       data-search="true"
                                       data-show-refresh="true"
                                       data-show-columns="true"
                                       data-sort-name="id"
                                       data-sort-order="desc"
                                       data-query-params="queryParams">

                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">#</th>
                                        <th data-field="no">No</th>
                                        <th data-field="class">Classe</th>
                                        <th data-field="subject">Matière</th>
                                        <th data-field="teacher">Enseignant</th>
                                        <th data-field="jour">Jour</th>
                                        <th data-field="horaire">Horaire</th>
                                        <th data-field="salle">Salle</th>
                                        <th data-field="trimester">Trimestre</th>
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
                window.location.href = `admin/emplois/${row.id}/edit`;
            },
            'click .deletedata': function (e, value, row, index) {
                if (confirm('Voulez-vous vraiment supprimer cet emploi du temps ?')) {
                    fetch(`admin/emplois/${row.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        $('#table_list').bootstrapTable('refresh');
                    });
                }
            }
        };
    </script>
@endsection
