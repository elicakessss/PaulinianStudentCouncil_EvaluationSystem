@extends('layouts.admin')

@section('title', 'Reassign Councils')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reassign Councils Before Deletion</h4>
                    <p class="text-muted mb-0">
                        The adviser <strong>{{ $adviser->first_name }} {{ $adviser->last_name }}</strong>
                        from {{ $adviser->department->name }} has {{ $adviser->councils->count() }} council(s) assigned.
                        Please reassign them to other advisers before proceeding with deletion.
                    </p>
                </div>
                <div class="card-body">
                    @if($availableAdvisers->isEmpty())
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> No Available Advisers</h5>
                            <p>There are no other advisers available for reassignment. You must create at least one other adviser before you can delete this one.</p>
                            <a href="{{ route('admin.users.create', ['role' => 'adviser']) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Adviser
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'adviser']) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Advisers
                            </a>
                        </div>
                    @else
                        <form action="{{ route('admin.users.reassign.process', $adviser->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Councils to Reassign:</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Council Name</th>
                                                    <th>Academic Year</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    <th>Reassign To</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($adviser->councils as $council)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $council->name }}</strong>
                                                    </td>
                                                    <td>{{ $council->academic_year }}</td>
                                                    <td>
                                                        @if($council->department)
                                                            {{ $council->department->abbreviation }}
                                                        @else
                                                            <span class="badge bg-info">University-wide</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $council->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ ucfirst($council->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <select name="reassignments[{{ $council->id }}]" class="form-select" required>
                                                            <option value="">-- Select New Adviser --</option>
                                                            @foreach($availableAdvisers as $availableAdviser)
                                                                <option value="{{ $availableAdviser->id }}">
                                                                    {{ $availableAdviser->first_name }} {{ $availableAdviser->last_name }}
                                                                    ({{ $availableAdviser->department->abbreviation }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        <h6><i class="fas fa-exclamation-triangle"></i> Warning</h6>
                                        <p class="mb-0">
                                            Once you proceed, the adviser <strong>{{ $adviser->first_name }} {{ $adviser->last_name }}</strong>
                                            will be permanently deleted and cannot be recovered. The selected councils will be reassigned to the chosen advisers.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.users.index', ['role' => 'adviser']) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to proceed with reassignment and deletion? This action cannot be undone.')">
                                    <i class="fas fa-exchange-alt"></i> Reassign Councils & Delete Adviser
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some visual feedback when selects change
    const selects = document.querySelectorAll('select[name^="reassignments"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endsection
