@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="page-title">Organizations</h1>

    <div class="card">
        <div class="card-body">
            <p>Organization list page - This will display all organizations</p>
            <a href="{{ route('admin.organization.create') }}" class="btn btn-success">Create New Organization</a>
        </div>
    </div>
</div>
@endsection
