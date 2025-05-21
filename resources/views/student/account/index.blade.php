@extends('layouts.student')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Account Management</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h2>Personal Details</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="profile-picture-container mb-3">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    <h3>{{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p class="text-muted">Student - {{ $user->department_name }}</p>
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <h4>ID Number</h4>
                        <p>{{ $user->id_number }}</p>
                    </div>
                    <div class="mb-3">
                        <h4>Department</h4>
                        <p>{{ $user->department_name }}</p>
                    </div>
                    <div class="mb-3">
                        <h4>Description</h4>
                        <p>{{ $user->description ?? 'No description provided.' }}</p>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('student.account.edit') }}" class="btn btn-success">Edit Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
