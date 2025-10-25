@extends('layouts.teacher.master')

@section('title', __('teacher.my_subjects'))

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('teacher.dashboard') }}">{{ __('teacher.dashboard') }}</a>
</li>
<li class="breadcrumb-item active">{{ __('teacher.my_subjects') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-book-open-variant me-2"></i>
                    {{ __('teacher.my_subjects') }}
                </h5>
            </div>
            <div class="card-body">
                @if(isset($subjectTeachers) && $subjectTeachers && count($subjectTeachers) > 0)
                    <div class="row">
                        @foreach($subjectTeachers as $subjectTeacher)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>{{ $subjectTeacher->subject->name ?? 'N/A' }}</h6>
                                        <p><strong>{{ __('teacher.department') }}:</strong> {{ $subjectTeacher->subject->specialite->departement->name ?? 'N/A' }}</p>
                                        <p><strong>{{ __('teacher.class') }}:</strong> {{ $subjectTeacher->classe->name ?? 'N/A' }}</p>
                                        <a href="{{ route('teacher.schedule', $subjectTeacher->id) }}" class="btn btn-primary btn-sm">
                                            {{ __('teacher.view_schedule') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center">{{ __('teacher.no_subjects') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
