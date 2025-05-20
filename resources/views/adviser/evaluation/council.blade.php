@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Council Evaluation: SITE Student Council</h1>
        <a href="{{ route('adviser.evaluation') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Evaluations
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Evaluation Progress</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Council:</strong> SITE Student Council</p>
                    <p><strong>Academic Year:</strong> 2024-2025</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Overall Progress:</strong></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                    </div>
                    <p class="text-muted">4 out of 8 evaluations completed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Student Evaluations</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>President</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm disabled">
                                    <i class="fas fa-clipboard-check"></i> Evaluate
                                </a>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>Vice President</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm disabled">
                                    <i class="fas fa-clipboard-check"></i> Evaluate
                                </a>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Mike Johnson</td>
                            <td>Secretary</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>
                                <a href="{{ route('adviser.evaluation.form', ['council_id' => 1, 'student_id' => 3]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Evaluate
                                </a>
                                <a href="#" class="btn btn-info btn-sm disabled">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Sarah Williams</td>
                            <td>Treasurer</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>
                                <a href="{{ route('adviser.evaluation.form', ['council_id' => 1, 'student_id' => 4]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Evaluate
                                </a>
                                <a href="#" class="btn btn-info btn-sm disabled">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
