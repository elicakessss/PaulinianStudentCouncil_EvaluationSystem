<?php

namespace App\Http\Controllers\Admin\SystemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use App\Models\Adviser;
use App\Models\Student;
use App\Models\Department;
use App\Models\Council;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role', 'student');

        switch ($role) {
            case 'admin':
                $users = Administrator::latest()->paginate(10);
                break;
            case 'adviser':
                $users = Adviser::with('department')->latest()->paginate(10);
                break;
            default:
                $users = Student::latest()->paginate(10);
                break;
        }

        return view('admin.system_management.user_management.index', compact('users', 'role'));
    }

    public function create(Request $request)
    {
        $role = $request->query('role', 'student');
        $departments = Department::all();

        // Auto-create departments if none exist
        if ($departments->isEmpty()) {
            Department::create(['name' => 'School of Arts, Sciences and Teacher Education', 'abbreviation' => 'SASTE']);
            Department::create(['name' => 'School of Business, Accountancy and Hospitality Management', 'abbreviation' => 'SBAHM']);
            Department::create(['name' => 'School of Information Technology Education', 'abbreviation' => 'SITE']);
            Department::create(['name' => 'School of Nursing and Allied Health Sciences', 'abbreviation' => 'SNAHS']);

            $departments = Department::all();
        }

        return view('admin.system_management.user_management.create', compact('role', 'departments'));
    }

    public function store(Request $request)
    {
        $role = $request->role;

        // Basic validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Add role-specific rules
        switch ($role) {
            case 'admin':
                $rules['id_number'] = 'required|string|max:255|unique:administrators,id_number';
                $rules['email'] = 'required|email|max:255|unique:administrators,email';
                break;
            case 'adviser':
                $rules['id_number'] = 'required|string|max:255|unique:advisers,id_number';
                $rules['email'] = 'required|email|max:255|unique:advisers,email';
                $rules['department_id'] = 'required|exists:departments,id';
                break;
            default: // student
                $rules['id_number'] = 'required|string|max:255|unique:students,id_number';
                $rules['email'] = 'required|email|max:255|unique:students,email';
                $rules['department_name'] = 'required|string|max:255';
                break;
        }

        $this->validate($request, $rules);

        // Create the user based on role
        switch ($role) {
            case 'admin':
                Administrator::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'id_number' => $request->id_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'description' => $request->description,
                ]);
                break;
            case 'adviser':
                Adviser::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'id_number' => $request->id_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'department_id' => $request->department_id,
                    'description' => $request->description,
                ]);
                break;
            default: // student
                Student::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'id_number' => $request->id_number,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'department_name' => $request->department_name,
                    'description' => $request->description,
                ]);
                break;
        }

        return redirect()->route('admin.system_management.user_management.index', ['role' => $role])
            ->with('success', ucfirst($role) . ' created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show($id, Request $request)
    {
        $role = $request->query('role', 'student');

        switch ($role) {
            case 'admin':
                $user = Administrator::findOrFail($id);
                break;
            case 'adviser':
                $user = Adviser::with('department')->findOrFail($id);
                break;
            default:
                $user = Student::findOrFail($id);
                break;
        }

        return view('admin.system_management.user_management.show', compact('user', 'role'));
    }

    public function edit($id, Request $request)
    {
        $role = $request->query('role', 'student');
        $departments = Department::all();

        switch ($role) {
            case 'admin':
                $user = Administrator::findOrFail($id);
                break;
            case 'adviser':
                $user = Adviser::with('department')->findOrFail($id);
                break;
            default:
                $user = Student::findOrFail($id);
                break;
        }

        return view('admin.system_management.user_management.edit', compact('user', 'role', 'departments'));
    }

    public function update($id, Request $request)
    {
        $role = $request->role;

        // Basic validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'id_number' => 'required|string|max:255',
        ];

        // Add password validation if it's being changed
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        // Add role-specific rules
        switch ($role) {
            case 'admin':
                $rules['id_number'] = ['required', 'string', 'max:255', Rule::unique('administrators')->ignore($id)];
                $rules['email'] = ['required', 'email', 'max:255', Rule::unique('administrators')->ignore($id)];
                break;
            case 'adviser':
                $rules['id_number'] = ['required', 'string', 'max:255', Rule::unique('advisers')->ignore($id)];
                $rules['email'] = ['required', 'email', 'max:255', Rule::unique('advisers')->ignore($id)];
                $rules['department_id'] = 'required|exists:departments,id';
                break;
            default: // student
                $rules['id_number'] = ['required', 'string', 'max:255', Rule::unique('students')->ignore($id)];
                $rules['email'] = ['required', 'email', 'max:255', Rule::unique('students')->ignore($id)];
                $rules['department_name'] = 'required|string|max:255';
                break;
        }

        $this->validate($request, $rules);

        // Update the user based on role
        switch ($role) {
            case 'admin':
                $user = Administrator::findOrFail($id);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->id_number = $request->id_number;
                $user->email = $request->email;
                $user->description = $request->description;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
                break;
            case 'adviser':
                $user = Adviser::findOrFail($id);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->id_number = $request->id_number;
                $user->email = $request->email;
                $user->department_id = $request->department_id;
                $user->description = $request->description;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
                break;
            default: // student
                $user = Student::findOrFail($id);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->id_number = $request->id_number;
                $user->email = $request->email;
                $user->department_name = $request->department_name;
                $user->description = $request->description;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
                break;
        }

        return redirect()->route('admin.system_management.user_management.index', ['role' => $role])
            ->with('success', ucfirst($role) . ' updated successfully');
    }

    /**
     * Show the reassignment page for adviser with councils
     */
    public function showReassignment($id)
    {
        $adviser = Adviser::with(['councils', 'department'])->findOrFail($id);

        // Get all other advisers (excluding the one being deleted)
        $availableAdvisers = Adviser::where('id', '!=', $id)
            ->with('department')
            ->get();

        return view('admin.system_management.user_management.reassign', compact('adviser', 'availableAdvisers'));
    }

    /**
     * Process the reassignment and then delete the adviser
     */
    public function processReassignment($id, Request $request)
    {
        $request->validate([
            'reassignments' => 'required|array',
            'reassignments.*' => 'required|exists:advisers,id'
        ]);

        try {
            DB::beginTransaction();

            $adviser = Adviser::with('councils')->findOrFail($id);

            // Reassign each council to the selected adviser
            foreach ($request->reassignments as $councilId => $newAdviserId) {
                Council::where('id', $councilId)
                    ->update(['adviser_id' => $newAdviserId]);
            }

            // Now delete the adviser
            $adviser->delete();

            DB::commit();

            return redirect()->route('admin.system_management.user_management.index', ['role' => 'adviser'])
                ->with('success', 'Adviser deleted successfully and councils have been reassigned.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred during reassignment. Please try again.']);
        }
    }

    public function destroy($id, Request $request)
    {
        $role = $request->query('role', 'student');

        switch ($role) {
            case 'admin':
                $user = Administrator::findOrFail($id);
                $user->delete();
                break;
            case 'adviser':
                $adviser = Adviser::with('councils')->findOrFail($id);

                // Check if adviser has any councils
                if ($adviser->councils->count() > 0) {
                    return redirect()->route('admin.system_management.user_management.reassign', $id)
                        ->with('info', 'This adviser has ' . $adviser->councils->count() . ' council(s) assigned. Please reassign them before deletion.');
                }

                // If no councils, proceed with deletion
                $adviser->delete();
                break;
            default:
                $user = Student::findOrFail($id);
                $user->delete();
                break;
        }

        return redirect()->route('admin.system_management.user_management.index', ['role' => $role])
            ->with('success', ucfirst($role) . ' deleted successfully');
    }
}
