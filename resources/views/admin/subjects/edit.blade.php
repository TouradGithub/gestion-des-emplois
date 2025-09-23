@extends('layouts.masters.master')

@section('content')
    <h2>{{ isset($subject) ? 'Edit' : 'Create' }} Subject</h2>
    <form action="{{ isset($subject) ? route('subjects.update', $subject) : route('subjects.store') }}" method="POST">
        @csrf
        @if (isset($subject)) @method('PUT') @endif

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Specialité</label>
            <select name="specialite_id" class="form-control">
                <option value="">-- None --</option>
                @foreach($specialites as $specialite)
                    <option value="{{ $specialite->id }}" {{ (old('specialite_id', $subject->specialite_id ?? '') == $specialite->id) ? 'selected' : '' }}>
                        {{ $specialite->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">{{ isset($subject) ? 'Update' : 'Create' }}</button>
    </form>
@endsection
