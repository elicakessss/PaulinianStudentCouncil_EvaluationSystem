@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Create New Council</h1>
        <a href="{{ route('admin.organization') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Organizations
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.organization.store') }}" method="POST" id="council-form">
        @csrf

        <!-- Council Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Council Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Council Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select @error('academic_year') is-invalid @enderror"
                                id="academic_year" name="academic_year" required>
                            <option value="">-- Select Academic Year --</option>
                            <option value="2024-2025" {{ old('academic_year') === '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                            <option value="2025-2026" {{ old('academic_year') === '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                            <option value="2026-2027" {{ old('academic_year') === '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                        </select>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="adviser_id" class="form-label">Assigned Adviser <span class="text-danger">*</span></label>
                        <select class="form-select @error('adviser_id') is-invalid @enderror"
                                id="adviser_id" name="adviser_id" required>
                            <option value="">-- Select Adviser --</option>
                            @foreach($advisers as $adviser)
                                <option value="{{ $adviser->id }}" {{ old('adviser_id') == $adviser->id ? 'selected' : '' }}>
                                    {{ $adviser->first_name }} {{ $adviser->last_name }}
                                    @if($adviser->department)
                                        ({{ $adviser->department->abbreviation }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('adviser_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select @error('department_id') is-invalid @enderror"
                                id="department_id" name="department_id">
                            <option value="">-- University-Wide Council --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Leave empty to create a university-wide council
                        </small>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Position Assignments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assign Students to Positions</h5>
                <button type="button" class="btn btn-outline-info btn-sm" id="refresh-students">
                    <i class="fas fa-sync-alt"></i> Refresh Students
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Select students for each position. Students will be filtered based on the selected department.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 30%">Position</th>
                                <th>Assigned Student</th>
                            </tr>
                        </thead>
                        <tbody id="positions-table">
                            @foreach($positions as $position)
                                <tr>
                                    <td>
                                        <strong>{{ $position->name }}</strong>
                                        @if($position->description)
                                            <small class="text-muted d-block">{{ $position->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-select" name="positions[{{ $position->id }}]">
                                            <option value="">-- Select Student --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}"
                                                        data-department="{{ $student->department_name }}"
                                                        {{ old("positions.{$position->id}") == $student->id ? 'selected' : '' }}>
                                                    {{ $student->first_name }} {{ $student->last_name }} ({{ $student->id_number }})
                                                    - {{ $student->department_name }}
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

        <!-- Submit Button -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Create Council
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const adviserSelect = document.getElementById('adviser_id');
    const positionSelects = document.querySelectorAll('select[name^="positions["]');

    function filterStudents() {
        const selectedDepartmentId = departmentSelect.value;
        const selectedAdviser = adviserSelect.value;

        // Get adviser's department if available
        let adviserDepartment = null;
        if (selectedAdviser) {
            const adviserOption = adviserSelect.querySelector(`option[value="${selectedAdviser}"]`);
            if (adviserOption) {
                adviserDepartment = adviserOption.textContent.match(/\(([^)]+)\)/);
                adviserDepartment = adviserDepartment ? adviserDepartment[1] : null;
            }
        }

        positionSelects.forEach(select => {
            const options = select.querySelectorAll('option[value!=""]');
            options.forEach(option => {
                const studentDepartment = option.getAttribute('data-department');
                let shouldShow = true;

                if (selectedDepartmentId && selectedDepartmentId !== '') {
                    // Filter by selected department
                    shouldShow = studentDepartment && studentDepartment.includes(departmentSelect.options[departmentSelect.selectedIndex].text);
                } else if (adviserDepartment) {
                    // If no department selected but adviser has department, filter by adviser's department
                    shouldShow = studentDepartment && studentDepartment.includes(adviserDepartment);
                }

                option.style.display = shouldShow ? 'block' : 'none';
            });
        });
    }

    departmentSelect.addEventListener('change', filterStudents);
    adviserSelect.addEventListener('change', filterStudents);

    document.getElementById('refresh-students').addEventListener('click', function() {
        location.reload();
    });

    // Form validation
    document.getElementById('council-form').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const academicYear = document.getElementById('academic_year').value;
        const adviserId = document.getElementById('adviser_id').value;

        if (!name || !academicYear || !adviserId) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
});
</script>
@endsection
