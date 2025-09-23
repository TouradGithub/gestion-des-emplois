// resources/views/anneescolaires/index.blade.php

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

                        <div class="row">
                            <div class="col-12">
                                <table class='table' id='table_list' data-toggle="table"
                                       data-url="{{ route('web.anneescolaires.list') }}"
                                       data-side-pagination="server" data-pagination="true"
                                       data-page-list="[5, 10, 20, 50, 100]"
                                       data-search="true" data-show-refresh="true"
                                       data-show-columns="true" data-sort-name="id" data-sort-order="desc"
                                       data-query-params="queryParams">
                                    <thead>
                                    <tr>
                                        <th data-field="id" data-visible="false">#</th>
                                        <th data-field="no">No</th>
                                        <th data-field="annee">Année</th>
                                        <th data-field="date_debut">Date début</th>
                                        <th data-field="date_fin">Date fin</th>
                                        <th data-field="is_active">Active</th>
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
                // Open edit form
            },
            'click .deletedata': function (e, value, row, index) {
                // Handle delete
            }
        };
    </script>
@endsection
