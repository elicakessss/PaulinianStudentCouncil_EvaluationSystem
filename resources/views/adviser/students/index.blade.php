@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Student List - {{ $department->name }} ({{ $department->abbreviation }})</h5>
            <a href="{{ route('adviser.students.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Student
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">&nbsp;</th>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($student->profile_picture)
                                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->id_number }}</td>
                                <td>{{ $student->email }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('adviser.students.edit', $student->id) }}" class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('adviser.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No students found in your department.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
