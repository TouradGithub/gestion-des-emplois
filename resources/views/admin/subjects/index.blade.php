@extends('layouts.masters.master')

@section('content')
    <h2>Subjects</h2>
    <a href="{{ route('web.subjects.create') }}" class="btn btn-primary mb-3">Add Subject</a>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Specialité</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->code }}</td>
                <td>{{ $subject->specialite?->name  .'  ( '.$subject->specialite?->niveau?->nom.' ) ' }}</td>
                <td>
                    <a href="{{ route('web.subjects.edit', $subject) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('web.subjects.destroy', $subject) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subject?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
