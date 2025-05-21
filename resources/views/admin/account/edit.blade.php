@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Account</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Profile Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.account.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $user->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.account') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Profile Picture -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Profile Picture</h4>
                </div>
                <div class="card-body text-center">
                    <div class="profile-picture-container mb-3">
                        @if($user->profile_picture)
                            <img id="profile-preview" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @else
                            <img id="profile-preview" src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    <form action="{{ route('admin.account.update-profile-picture') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Select New Profile Picture</label>
                            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture" accept="image/*">
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Maximum file size: 2MB.</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Upload Picture</button>
                    </form>
                </div>
            </div>

            <!-- Password Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.account.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="alert alert-info">
                            Passwords must be 6 digits (numbers only).
                        </div>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profilePictureInput = document.getElementById('profile_picture');

    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewImage = document.getElementById('profile-preview');

                    if (previewImage) {
                        previewImage.src = e.target.result;
                    }
                };

                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
