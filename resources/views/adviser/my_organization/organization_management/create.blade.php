@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Council</h1>
        <a href="{{ route('adviser.organization') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Councils
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Council Information</div>
        <div class="card-body">
            <form action="{{ route('adviser.organization.store') }}" method="POST" id="council-form">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Council Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <select class="form-control" id="academic_year" name="academic_year" required>
                            <option value="">-- Select Academic Year --</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" value="{{ Auth::guard('adviser')->user()->department->name ?? 'Your Department' }}" disabled>
                    <small class="text-muted">Council will be automatically assigned to your department</small>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Assign Students to Positions</span>
            <button type="button" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync-alt"></i> Refresh Available Students
            </button>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Assign students from your department to positions in this council.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30%">Position</th>
                            <th>Assigned Student</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Executive Branch -->
                        <tr class="table-light">
                            <td colspan="2"><strong>Executive Branch</strong></td>
                        </tr>
                        <tr>
                            <td>President</td>
                            <td>
                                <select class="form-control" name="positions[1]" form="council-form">
                                    <option value="">-- Select Student --</option>
                                    <option value="1">John Doe (2024-1001)</option>
                                    <option value="3">Alex Johnson (2024-1005)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Vice President</td>
                            <td>
                                <select class="form-control" name="positions[2]" form="council-form">
                                    <option value="">-- Select Student --</option>
                                    <option value="2">Jane Smith (2024-1002)</option>
                                    <option value="3">Alex Johnson (2024-1005)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Secretary</td>
                            <td>
                                <select class="form-control" name="positions[3]" form="council-form">
                                    <option value="">-- Select Student --</option>
                                    <option value="2">Jane Smith (2024-1002)</option>
                                    <option value="3">Alex Johnson (2024-1005)</option>
                                </select>
                            </td>
                        </tr>

                        <!-- Legislative Branch -->
                        <tr class="table-light">
                            <td colspan="2"><strong>Legislative Branch</strong></td>
                        </tr>
                        <tr>
                            <td>Senator - Year Level 1</td>
                            <td>
                                <select class="form-control" name="positions[4]" form="council-form">
                                    <option value="">-- Select Student --</option>
                                    <option value="3">Alex Johnson (2024-1005)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Senator - Year Level 2</td>
                            <td>
                                <select class="form-control" name="positions[5]" form="council-form">
                                    <option value="">-- Select Student --</option>
                                    <option value="3">Alex Johnson (2024-1005)</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success" form="council-form">
                    <i class="fas fa-save"></i> Create Council
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
