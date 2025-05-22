@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My University-Wide Councils</h1>
        <a href="#" class="btn btn-success">
            <i class="fas fa-plus"></i> Create New University-Wide Council
        </a>
    </div>

    <div class="alert alert-info">
        <p><strong>University-Wide Council Management</strong></p>
        <p>This section allows administrators to manage university-wide student councils that span across all departments.</p>

        @if(isset($councils) && $councils->count() > 0)
            <p>You have {{ $councils->count() }} university-wide councils.</p>
        @else
            <p>No university-wide councils found. Create your first council to get started.</p>
        @endif
    </div>

    @if(isset($councils))
        <div class="card">
            <div class="card-header">
                <h5>University-Wide Councils</h5>
            </div>
            <div class="card-body">
                @if($councils->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Council Name</th>
                                    <th>Academic Year</th>
                                    <th>Adviser</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($councils as $council)
                                    <tr>
                                        <td>{{ $council->name }}</td>
                                        <td>{{ $council->academic_year }}</td>
                                        <td>{{ $council->adviser->first_name ?? 'N/A' }} {{ $council->adviser->last_name ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $council->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($council->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No councils to display.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
