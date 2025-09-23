@extends('layouts.masters.master')

@section('title')
    Modifier Niveau
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Modifier Niveau</h3>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('web.niveau-pedagogiques.update', $niveauPedagogique->id) }}" method="POST">
                    @method('PUT')
                    @include('admin.niveau-pedagogiques.form', ['niveau' => $niveauPedagogique])
                </form>
            </div>
        </div>
    </div>
@endsection
