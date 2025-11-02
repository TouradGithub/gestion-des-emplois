@extends('layouts.masters.master')

@section('title', __('messages.list_students'))

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">{{ __('messages.list_students') }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('web.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.list_students') }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">{{ __('messages.list_students') }}</h4>
                            <a href="{{ route('web.students.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> {{ __('messages.add_student') }}
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>{{ __('messages.image') }}</th>
                                        <th>{{ __('messages.nni') }}</th>
                                        <th>{{ __('messages.fullname') }}</th>
                                        <th>{{ __('messages.parent_name') }}</th>
                                        <th>{{ __('messages.phone') }}</th>
                                        <th>{{ __('messages.class') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                @php
                                                    $imageUrl = $student->image ? Storage::url($student->image) : asset('images/default-student.svg');
                                                @endphp
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $student->fullname }}"
                                                     class="rounded-circle"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            </td>
                                            <td><strong>{{ $student->nni }}</strong></td>
                                            <td>{{ $student->fullname }}</td>
                                            <td>{{ $student->parent_name }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $student->classe->nom }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('web.students.show', $student) }}"
                                                       class="btn btn-info btn-sm" title="{{ __('messages.show') }}">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('web.students.edit', $student) }}"
                                                       class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-student"
                                                            data-id="{{ $student->id }}"
                                                            data-name="{{ $student->fullname }}"
                                                            title="{{ __('messages.delete') }}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="mdi mdi-account-multiple-outline text-muted" style="font-size: 4rem;"></i>
                                                    <p class="text-muted mt-2">{{ __('messages.no_students_found') }}</p>
                                                    <a href="{{ route('web.students.create') }}" class="btn btn-primary">
                                                        <i class="mdi mdi-plus"></i> {{ __('messages.add_first_student') }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Delete student functionality
    $('.delete-student').click(function() {
        const studentId = $(this).data('id');
        const studentName = $(this).data('name');
        const row = $(this).closest('tr');

        Swal.fire({
            title: '{{ __('messages.are_you_sure') }}',
            text: `{{ __('messages.confirm_delete_student') }} "${studentName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __('messages.yes_delete') }}',
            cancelButtonText: '{{ __('messages.cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `/web/students/${studentId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: '{{ __('messages.deleted') }}!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            row.fadeOut(500, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = '{{ __('messages.delete_error') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: '{{ __('messages.error') }}!',
                            text: message,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
