@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Add New {{ ucfirst($role) }}</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ ucfirst($role) }} Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                               id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                               id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                               id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                        @error('id_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($role == 'student')
                <div class="mb-3">
                    <label for="department_name" class="form-label">Department</label>
                    <select class="form-control @error('department_name') is-invalid @enderror"
                            id="department_name" name="department_name" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->name }}" {{ old('department_name') == $dept->name ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($departments->isEmpty())
                        <div class="text-danger mt-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            No departments are available. Please create departments first.
                        </div>
                    @endif
                </div>
                @elseif($role == 'adviser')
                <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-control @error('department_id') is-invalid @enderror"
                            id="department_id" name="department_id" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($departments->isEmpty())
                        <div class="text-danger mt-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            No departments are available. Please create departments first.
                        </div>
                    @endif
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index', ['role' => $role]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save {{ ucfirst($role) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
