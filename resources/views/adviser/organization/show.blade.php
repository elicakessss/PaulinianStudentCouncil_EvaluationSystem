@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>SITE Student Council</h1>
        <a href="{{ route('adviser.organization') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Councils
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">Council Information</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Name:</strong> SITE Student Council</p>
                    <p class="mb-2"><strong>Academic Year:</strong> 2024-2025</p>
                    <p class="mb-2"><strong>Department:</strong> School of Information Technology Education</p>
                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                    <p class="mb-0"><strong>Created:</strong> January 15, 2024</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Council Members</span>
                    <a href="#" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus"></i> Assign Student
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ID Number</th>
                                    <th>Position</th>
                                    <th>Branch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>2024-1001</td>
                                    <td>President</td>
                                    <td>Executive</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>2024-1002</td>
                                    <td>Vice President</td>
                                    <td>Executive</td>
                                </tr>
                                <tr>
                                    <td>Mike Johnson</td>
                                    <td>2024-1003</td>
                                    <td>Secretary</td>
                                    <td>Executive</td>
                                </tr>
                                <tr>
                                    <td>Sarah Williams</td>
                                    <td>2024-1004</td>
                                    <td>Treasurer</td>
                                    <td>Executive</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
