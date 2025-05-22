@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Student Account Details</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
            <div>
                <a href="{{ route('adviser.my_organization.student_management.edit', $student->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Edit Account
                </a>
                <a href="{{ route('adviser.my_organization.student_management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}"
                                 alt="Profile Picture"
                                 class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}"
                                 alt="Default Profile Picture"
                                 class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>
                    <h6 class="text-muted">Student</h6>
                    <span class="badge bg-primary">{{ $department->abbreviation }}</span>
                </div>
                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>First Name:</strong>
                            <p class="mb-0">{{ $student->first_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Name:</strong>
                            <p class="mb-0">{{ $student->last_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Student ID:</strong>
                            <p class="mb-0">{{ $student->id_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="mb-0">{{ $student->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Department:</strong>
                            <p class="mb-0">{{ $student->department_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Department Code:</strong>
                            <p class="mb-0">{{ $department->abbreviation }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Account Created:</strong>
                            <p class="mb-0">{{ $student->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <p class="mb-0">{{ $student->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($student->description)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p class="mb-0">{{ $student->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if(isset($student->councilPositions) && $student->councilPositions->count() > 0)
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Council Memberships ({{ $student->councilPositions->count() }}):</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Council Name</th>
                                    <th>Position</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->councilPositions as $councilPosition)
                                <tr>
                                    <td>{{ $councilPosition->council->name ?? 'N/A' }}</td>
                                    <td>{{ $councilPosition->position->name ?? 'N/A' }}</td>
                                    <td>{{ $councilPosition->council->academic_year ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $councilPosition->council->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($councilPosition->council->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <hr>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> This student is not currently assigned to any councils.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
