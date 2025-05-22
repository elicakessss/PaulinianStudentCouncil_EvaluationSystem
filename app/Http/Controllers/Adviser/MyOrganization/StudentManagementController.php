<?php

namespace App\Http\Controllers\Adviser\MyOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentManagementController extends Controller
{
    /**
     * Display a listing of students in the adviser's department.
     */
    public function index()
    {
        // Get the current adviser
        $adviser = Auth::guard('adviser')->user();

        // Get the department of the adviser
        $department = $adviser->department;

        // Get students from the adviser's department
        $students = Student::where('department_name', $department->name)
            ->latest()
            ->paginate(10);

        return view('adviser.my_organization.student_management.index', compact('students', 'department'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        return view('adviser.my_organization.student_management.create', compact('department'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        // Validate the request
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:students,id_number',
            'email' => 'required|email|max:255|unique:students,email',
            'password' => 'required|string|min:6|confirmed',
            'description' => 'nullable|string',
        ]);

        // Create the student with the adviser's department
        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'id_number' => $request->id_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_name' => $department->name, // Automatically assign to adviser's department
            'description' => $request->description,
        ]);

        return redirect()->route('adviser.my_organization.student_management.index')
            ->with('success', 'Student created successfully');
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        // Find the student
        $student = Student::findOrFail($id);

        // Check if the student belongs to the adviser's department
        if ($student->department_name !== $department->name) {
            return redirect()->route('adviser.my_organization.student_management.index')
                ->with('error', 'You are not authorized to view this student');
        }

        return view('adviser.my_organization.student_management.show', compact('student', 'department'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        // Find the student
        $student = Student::findOrFail($id);

        // Check if the student belongs to the adviser's department
        if ($student->department_name !== $department->name) {
            return redirect()->route('adviser.my_organization.student_management.index')
                ->with('error', 'You are not authorized to edit this student');
        }

        return view('adviser.my_organization.student_management.edit', compact('student', 'department'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        // Find the student
        $student = Student::findOrFail($id);

        // Check if the student belongs to the adviser's department
        if ($student->department_name !== $department->name) {
            return redirect()->route('adviser.my_organization.student_management.index')
                ->with('error', 'You are not authorized to update this student');
        }

        // Validate the request
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('students')->ignore($id)],
            'description' => 'nullable|string',
        ]);

        // Update student data
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->id_number = $request->id_number;
        $student->email = $request->email;
        $student->description = $request->description;

        // Update password if provided
        if ($request->filled('password')) {
            $this->validate($request, [
                'password' => 'string|min:6|confirmed',
            ]);

            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('adviser.my_organization.student_management.index')
            ->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        // Get the current adviser's department
        $adviser = Auth::guard('adviser')->user();
        $department = $adviser->department;

        // Find the student
        $student = Student::findOrFail($id);

        // Check if the student belongs to the adviser's department
        if ($student->department_name !== $department->name) {
            return redirect()->route('adviser.my_organization.student_management.index')
                ->with('error', 'You are not authorized to delete this student');
        }

        // Delete the student
        $student->delete();

        return redirect()->route('adviser.my_organization.student_management.index')
            ->with('success', 'Student deleted successfully');
    }
}
