@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">User Management</h1>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ ucfirst($role ?? 'Student') }} List</h5>
            <a href="{{ route('admin.users.create', ['role' => $role ?? 'student']) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add {{ ucfirst($role ?? 'Student') }}
            </a>
        </div>
        <div class="card-body">
            <!-- Role selector tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ ($role ?? 'student') == 'student' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'student']) }}">Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($role ?? '') == 'adviser' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'adviser']) }}">Advisers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($role ?? '') == 'admin' ? 'active' : '' }}" href="{{ route('admin.users.index', ['role' => 'admin']) }}">Administrators</a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Email</th>
                            @if(($role ?? 'student') != 'admin')
                            <th>Department</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->id_number }}</td>
                            <td>{{ $user->email }}</td>
                            @if(($role ?? 'student') == 'student')
                            <td>{{ $user->department_name }}</td>
                            @elseif(($role ?? '') == 'adviser')
                            <td>{{ $user->department->name ?? 'N/A' }}</td>
                            @endif
                            <td>
                                <a href="{{ route('admin.users.edit', ['id' => $user->id, 'role' => $role ?? 'student']) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form class="d-inline" action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="role" value="{{ $role ?? 'student' }}">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ ($role ?? 'student') == 'admin' ? 4 : 5 }}" class="text-center">No {{ ucfirst($role ?? 'student') }}s found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->appends(['role' => $role ?? 'student'])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
