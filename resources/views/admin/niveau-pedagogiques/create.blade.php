@extends('layouts.masters.master')

@section('title')
    Ajouter un Niveau Pédagogique
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Ajouter un Niveau</h3>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('web.niveau-pedagogiques.store') }}" method="POST">
                    @include('admin.niveau-pedagogiques.form')
                </form>
            </div>
        </div>
    </div>
@endsection
