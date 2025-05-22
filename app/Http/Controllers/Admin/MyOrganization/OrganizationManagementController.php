<?php

namespace App\Http\Controllers\Admin\MyOrganization;

use App\Http\Controllers\Controller;
use App\Models\Council;
use App\Models\Department;
use App\Models\Adviser;
use App\Models\Position;
use App\Models\Student;
use App\Models\CouncilPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrganizationManagementController extends Controller
{
    /**
     * Display admin's university-wide councils
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Get only university-wide councils (department_id is null)
        $query = Council::with(['adviser', 'councilPositions.student', 'councilPositions.position'])
            ->whereNull('department_id'); // University-wide councils only

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $councils = $query->orderBy('created_at', 'desc')->paginate(12);
        $academicYears = Council::whereNull('department_id')
            ->distinct()
            ->pluck('academic_year')
            ->sort()
            ->values();

        return view('admin.my_organization.organization_management.index', compact('councils', 'academicYears'));
    }

    /**
     * Show the form for creating a new university-wide council
     */
    public function create()
    {
        $advisers = Adviser::with('department')->orderBy('first_name')->get();
        $positions = Position::orderBy('name')->get();
        $students = Student::orderBy('first_name')->get();

        return view('admin.my_organization.organization_management.create', compact('advisers', 'positions', 'students'));
    }

    /**
     * Store a newly created university-wide council
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'adviser_id' => 'required|exists:advisers,id',
            'description' => 'nullable|string',
            'positions' => 'array',
            'positions.*' => 'nullable|exists:students,id'
        ]);

        // Validate unique council name per academic year
        $existingCouncil = Council::where('name', $request->name)
            ->where('academic_year', $request->academic_year)
            ->whereNull('department_id') // Check only university-wide councils
            ->first();

        if ($existingCouncil) {
            return back()->withInput()
                ->withErrors(['name' => 'A university-wide council with this name already exists for the selected academic year.']);
        }

        try {
            DB::beginTransaction();

            // Create the university-wide council (department_id = null)
            $council = Council::create([
                'name' => $request->name,
                'academic_year' => $request->academic_year,
                'adviser_id' => $request->adviser_id,
                'department_id' => null, // University-wide
                'description' => $request->description,
                'status' => 'active'
            ]);

            // Assign positions if provided
            if ($request->filled('positions')) {
                foreach ($request->positions as $positionId => $studentId) {
                    if ($studentId) {
                        CouncilPosition::create([
                            'council_id' => $council->id,
                            'position_id' => $positionId,
                            'student_id' => $studentId
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('University-wide council created by admin', [
                'council_id' => $council->id,
                'council_name' => $council->name,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.my_organization.organization_management.index')
                ->with('success', 'University-wide council created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to create university-wide council', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('error', 'Failed to create council. Please try again.');
        }
    }

    /**
     * Display the specified university-wide council
     */
    public function show($id)
    {
        $council = Council::with([
            'adviser',
            'councilPositions.position',
            'councilPositions.student'
        ])->whereNull('department_id') // Ensure it's university-wide
          ->findOrFail($id);

        $availableStudents = Student::whereDoesntHave('councilPositions', function($query) use ($council) {
            $query->where('council_id', $council->id);
        })->orderBy('first_name')->get();

        $availablePositions = Position::whereDoesntHave('councilPositions', function($query) use ($council) {
            $query->where('council_id', $council->id);
        })->orderBy('name')->get();

        return view('admin.my_organization.organization_management.show', compact('council', 'availableStudents', 'availablePositions'));
    }

    /**
     * Show the form for editing the specified university-wide council
     */
    public function edit($id)
    {
        $council = Council::with(['councilPositions.position', 'councilPositions.student'])
            ->whereNull('department_id') // Ensure it's university-wide
            ->findOrFail($id);

        $advisers = Adviser::with('department')->orderBy('first_name')->get();
        $positions = Position::orderBy('name')->get();
        $students = Student::orderBy('first_name')->get();

        return view('admin.my_organization.organization_management.edit', compact('council', 'advisers', 'positions', 'students'));
    }

    /**
     * Update the specified university-wide council
     */
    public function update(Request $request, $id)
    {
        $council = Council::whereNull('department_id')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'adviser_id' => 'required|exists:advisers,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,deactivated',
            'positions' => 'array',
            'positions.*' => 'nullable|exists:students,id'
        ]);

        // Validate unique council name per academic year (excluding current council)
        $existingCouncil = Council::where('name', $request->name)
            ->where('academic_year', $request->academic_year)
            ->whereNull('department_id')
            ->where('id', '!=', $council->id)
            ->first();

        if ($existingCouncil) {
            return back()->withInput()
                ->withErrors(['name' => 'A university-wide council with this name already exists for the selected academic year.']);
        }

        try {
            DB::beginTransaction();

            // Update council
            $council->update([
                'name' => $request->name,
                'academic_year' => $request->academic_year,
                'adviser_id' => $request->adviser_id,
                'description' => $request->description,
                'status' => $request->status
            ]);

            // Update positions
            if ($request->has('positions')) {
                // Remove existing positions
                $council->councilPositions()->delete();

                // Add new positions
                foreach ($request->positions as $positionId => $studentId) {
                    if ($studentId) {
                        CouncilPosition::create([
                            'council_id' => $council->id,
                            'position_id' => $positionId,
                            'student_id' => $studentId
                        ]);
                    }
                }
            }

            DB::commit();

            Log::info('University-wide council updated by admin', [
                'council_id' => $council->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.my_organization.organization_management.show', $council->id)
                ->with('success', 'Council updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update university-wide council', ['error' => $e->getMessage()]);

            return back()->withInput()
                ->with('error', 'Failed to update council. Please try again.');
        }
    }

    /**
     * Remove the specified university-wide council
     */
    public function destroy($id)
    {
        try {
            $council = Council::whereNull('department_id')->findOrFail($id);

            // Instead of deleting, deactivate the council
            $council->update(['status' => 'deactivated']);

            Log::info('University-wide council deactivated by admin', [
                'council_id' => $council->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.my_organization.organization_management.index')
                ->with('success', 'Council deactivated successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to deactivate university-wide council', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to deactivate council. Please try again.');
        }
    }

    /**
     * Assign student to position in university-wide council
     */
    public function assignStudent(Request $request, $councilId)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'student_id' => 'required|exists:students,id'
        ]);

        try {
            $council = Council::whereNull('department_id')->findOrFail($councilId);

            // Check if position is already filled
            $existingPosition = CouncilPosition::where('council_id', $councilId)
                ->where('position_id', $request->position_id)
                ->first();

            if ($existingPosition) {
                return back()->with('warning', 'Position is already filled!');
            }

            CouncilPosition::create([
                'council_id' => $councilId,
                'position_id' => $request->position_id,
                'student_id' => $request->student_id
            ]);

            Log::info('Student assigned to university-wide council position by admin', [
                'council_id' => $councilId,
                'student_id' => $request->student_id,
                'position_id' => $request->position_id,
                'admin_id' => auth()->id()
            ]);

            return back()->with('success', 'Student assigned successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to assign student to university-wide council', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to assign student. Please try again.');
        }
    }

    /**
     * Remove student from position in university-wide council
     */
    public function removeStudent($councilId, $positionId)
    {
        try {
            $council = Council::whereNull('department_id')->findOrFail($councilId);

            $councilPosition = CouncilPosition::where('council_id', $councilId)
                ->where('position_id', $positionId)
                ->firstOrFail();

            $councilPosition->delete();

            Log::info('Student removed from university-wide council position by admin', [
                'council_id' => $councilId,
                'position_id' => $positionId,
                'admin_id' => auth()->id()
            ]);

            return back()->with('success', 'Student removed from position successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to remove student from university-wide council', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to remove student. Please try again.');
        }
    }
}
