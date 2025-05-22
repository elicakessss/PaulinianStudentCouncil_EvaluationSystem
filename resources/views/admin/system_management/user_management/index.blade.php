@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>User Management</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ ucfirst($role) }} List</h5>
            <a href="{{ route('admin.system_management.user_management.create', ['role' => $role]) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add {{ ucfirst($role) }}
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ $role === 'student' ? 'active' : '' }}" href="{{ route('admin.system_management.user_management.index', ['role' => 'student']) }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $role === 'adviser' ? 'active' : '' }}" href="{{ route('admin.system_management.user_management.index', ['role' => 'adviser']) }}">Advisers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $role === 'admin' ? 'active' : '' }}" href="{{ route('admin.system_management.user_management.index', ['role' => 'admin']) }}">Administrators</a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">&nbsp;</th>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Email</th>
                            @if($role === 'adviser' || $role === 'student')
                                <th>Department</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->id_number }}</td>
                                <td>{{ $user->email }}</td>
                                @if($role === 'adviser')
                                    <td>{{ $user->department->name ?? 'No Department' }}</td>
                                @elseif($role === 'student')
                                    <td>{{ $user->department_name }}</td>
                                @endif
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.system_management.user_management.show', ['id' => $user->id, 'role' => $role]) }}" class="btn btn-sm btn-info me-2" title="View Account">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.system_management.user_management.edit', ['id' => $user->id, 'role' => $role]) }}" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.system_management.user_management.destroy', ['id' => $user->id, 'role' => $role]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this {{ $role }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($role === 'adviser' || $role === 'student') ? 6 : 5 }}" class="text-center">No {{ $role }}s found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(['role' => $role])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
