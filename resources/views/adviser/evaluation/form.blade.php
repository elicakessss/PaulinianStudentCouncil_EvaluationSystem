@extends('layouts.adviser')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Evaluate Student: Mike Johnson</h1>
        <a href="{{ route('adviser.evaluation.council', 1) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Council Evaluation
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Student Information</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> Mike Johnson</p>
                    <p><strong>ID Number:</strong> 2024-1003</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Position:</strong> Secretary</p>
                    <p><strong>Council:</strong> SITE Student Council (2024-2025)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Evaluation Form</div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="council_id" value="1">
                <input type="hidden" name="student_id" value="3">

                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> This is a placeholder for the evaluation form. The actual form will include various criteria for evaluation.
                </div>

                <!-- Placeholder for evaluation criteria -->
                <div class="mb-4">
                    <h5>Leadership Skills</h5>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="mb-0">1. Demonstrates effective leadership and decision-making abilities</p>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="criteria_1" id="criteria_1_1" value="1">
                                <label class="btn btn-outline-secondary" for="criteria_1_1">1</label>

                                <input type="radio" class="btn-check" name="criteria_1" id="criteria_1_2" value="2">
                                <label class="btn btn-outline-secondary" for="criteria_1_2">2</label>

                                <input type="radio" class="btn-check" name="criteria_1" id="criteria_1_3" value="3">
                                <label class="btn btn-outline-secondary" for="criteria_1_3">3</label>

                                <input type="radio" class="btn-check" name="criteria_1" id="criteria_1_4" value="4">
                                <label class="btn btn-outline-secondary" for="criteria_1_4">4</label>

                                <input type="radio" class="btn-check" name="criteria_1" id="criteria_1_5" value="5">
                                <label class="btn btn-outline-secondary" for="criteria_1_5">5</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <p class="mb-0">2. Motivates and inspires other council members</p>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="criteria_2" id="criteria_2_1" value="1">
                                <label class="btn btn-outline-secondary" for="criteria_2_1">1</label>

                                <input type="radio" class="btn-check" name="criteria_2" id="criteria_2_2" value="2">
                                <label class="btn btn-outline-secondary" for="criteria_2_2">2</label>

                                <input type="radio" class="btn-check" name="criteria_2" id="criteria_2_3" value="3">
                                <label class="btn btn-outline-secondary" for="criteria_2_3">3</label>

                                <input type="radio" class="btn-check" name="criteria_2" id="criteria_2_4" value="4">
                                <label class="btn btn-outline-secondary" for="criteria_2_4">4</label>

                                <input type="radio" class="btn-check" name="criteria_2" id="criteria_2_5" value="5">
                                <label class="btn btn-outline-secondary" for="criteria_2_5">5</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Communication & Teamwork</h5>
                    <!-- More evaluation criteria would go here -->
                    <p class="text-muted">Additional evaluation criteria would be shown here...</p>
                </div>

                <div class="mb-4">
                    <label for="comments" class="form-label">Additional Comments</label>
                    <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Submit Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
