@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="page-title">User Management</h1>

    <div class="card">
        <div class="card-body">
            <p>User management list page</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
        </div>
    </div>
</div>
@endsection
