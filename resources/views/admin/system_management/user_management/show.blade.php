@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">{{ ucfirst($role) }} Account Details</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h5>
            <div>
                <a href="{{ route('admin.system_management.user_management.edit', ['id' => $user->id, 'role' => $role]) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Edit Account
                </a>
                <a href="{{ route('admin.system_management.user_management.index', ['role' => $role]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}"
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
                    <h6 class="text-muted">{{ ucfirst($role) }}</h6>
                </div>
                <div class="col-md-9">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>First Name:</strong>
                            <p class="mb-0">{{ $user->first_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Name:</strong>
                            <p class="mb-0">{{ $user->last_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID Number:</strong>
                            <p class="mb-0">{{ $user->id_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($role === 'adviser' && isset($user->department))
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Department:</strong>
                            <p class="mb-0">{{ $user->department->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Department Code:</strong>
                            <p class="mb-0">{{ $user->department->abbreviation }}</p>
                        </div>
                    </div>
                    @endif

                    @if($role === 'student' && isset($user->department_name))
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Department:</strong>
                            <p class="mb-0">{{ $user->department_name }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Account Created:</strong>
                            <p class="mb-0">{{ $user->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong>
                            <p class="mb-0">{{ $user->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    @if($user->description)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p class="mb-0">{{ $user->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($role === 'adviser' && isset($user->councils) && $user->councils->count() > 0)
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Managed Councils ({{ $user->councils->count() }}):</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Council Name</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->councils as $council)
                                <tr>
                                    <td>{{ $council->name }}</td>
                                    <td>{{ $council->academic_year }}</td>
                                    <td>
                                        <span class="badge {{ $council->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($council->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($council->department)
                                            {{ $council->department->abbreviation }}
                                        @else
                                            <span class="badge bg-info">University-wide</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
