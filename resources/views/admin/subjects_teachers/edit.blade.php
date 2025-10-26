@extends('layouts.masters.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تعديل تخصيص المادة للأستاذ</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('web.subjects_teachers.update', $subjectTeacher->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="teacher_id" class="col-md-4 col-form-label text-md-end">الأستاذ</label>

                            <div class="col-md-6">
                                <select id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" name="teacher_id" required>
                                    <option value="">اختر الأستاذ</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $subjectTeacher->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('teacher_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subject_id" class="col-md-4 col-form-label text-md-end">المادة</label>

                            <div class="col-md-6">
                                <select id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" name="subject_id" required>
                                    <option value="">اختر المادة</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $subjectTeacher->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('subject_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="class_id" class="col-md-4 col-form-label text-md-end">الصف</label>

                            <div class="col-md-6">
                                <select id="class_id" class="form-control @error('class_id') is-invalid @enderror" name="class_id" required>
                                    <option value="">اختر الصف</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $subjectTeacher->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->nom }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('class_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="trimester_id" class="col-md-4 col-form-label text-md-end">الفصل</label>

                            <div class="col-md-6">
                                <select id="trimester_id" class="form-control @error('trimester_id') is-invalid @enderror" name="trimester_id" required>
                                    <option value="">اختر الفصل</option>
                                    @foreach($trimesters as $trimester)
                                        <option value="{{ $trimester->id }}" {{ old('trimester_id', $subjectTeacher->trimester_id) == $trimester->id ? 'selected' : '' }}>
                                            {{ $trimester->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('trimester_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="annee_id" class="col-md-4 col-form-label text-md-end">السنة الدراسية</label>

                            <div class="col-md-6">
                                <select id="annee_id" class="form-control @error('annee_id') is-invalid @enderror" name="annee_id" required>
                                    <option value="">اختر السنة الدراسية</option>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}" {{ old('annee_id', $subjectTeacher->annee_id) == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->annee }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('annee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    تحديث
                                </button>
                                <a href="{{ route('web.subjects_teachers.index') }}" class="btn btn-secondary">
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
