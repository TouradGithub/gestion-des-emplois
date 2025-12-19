@extends('layouts.masters.master')

@section('title')
{{ trans('teacher.teachers') }}
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Affectation des proff</h3>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col text-end">
                            <a href="{{ route('web.subjects_teachers.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> {{ __('Affecter des proff a matiere') }}
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class='table' id='table_list' data-toggle="table"
                                   data-url="{{ route('web.subjects_teachers.list') }}"
                                   data-side-pagination="server" data-pagination="true"
                                   data-page-list="[5, 10, 20, 50, 100]"
                                   data-search="true" data-show-refresh="true"
                                   data-show-columns="true" data-sort-name="id" data-sort-order="desc"
                                   data-query-params="queryParams">
                                <thead>
                                <tr>
                                    <th data-field="id" data-visible="false">#</th>
                                    <th data-field="no">No</th>
                                    <th data-field="subject">Matiere</th>
                                    <th data-field="teacher">Professeur</th>
                                    <th data-field="trimester">Trimestre</th>
                                    <th data-field="classe">Classe</th>
                                    <th data-field="heures_trimestre">Heures/Trimestre</th>
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
            window.location.href = '/admin/subjects_teachers/' + row.id + '/edit';
        },
        'click .deletedata': function (e, value, row, index) {
            if (confirm('Voulez-vous vraiment supprimer cette affectation ?')) {
                fetch('/admin/subjects_teachers/' + row.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        $('#table_list').bootstrapTable('refresh');
                        alert('Affectation supprimée avec succès');
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                }).catch(error => {
                    alert('Erreur lors de la suppression');
                });
            }
        }
    };
</script>
@endsection
