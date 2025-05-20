<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Council;
use App\Models\Student;
use App\Models\Position;
use App\Models\CouncilPosition;
use App\Services\CouncilService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    protected $councilService;

    public function __construct(CouncilService $councilService)
    {
        $this->councilService = $councilService;
    }

    public function index(Request $request)
    {
        $adviser = Auth::guard('adviser')->user();

        $query = Council::where('adviser_id', $adviser->id);

        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('academic_year') && $request->academic_year != '') {
            $query->where('academic_year', $request->academic_year);
        }

        $councils = $query->with('department')->latest()->paginate(10);
        $academicYears = Council::where('adviser_id', $adviser->id)
            ->select('academic_year')
            ->distinct()
            ->pluck('academic_year');

        return view('adviser.organizations.index', compact('councils', 'academicYears'));
    }

    public function create()
    {
        $adviser = Auth::guard('adviser')->user();
        $academicYears = [
            (date('Y') - 1) . ' - ' . date('Y'),
            date('Y') . ' - ' . (date('Y') + 1),
            (date('Y') + 1) . ' - ' . (date('Y') + 2),
        ];

        return view('adviser.organizations.create', compact('adviser', 'academicYears'));
    }

    public function store(Request $request)
    {
        $adviser = Auth::guard('adviser')->user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
        ]);

        // Automatically set the adviser_id to the current adviser
        $data = $request->all();
        $data['adviser_id'] = $adviser->id;

        try {
            $council = $this->councilService->createCouncil($data, $adviser);

            activity()
                ->causedBy($adviser)
                ->performedOn($council)
                ->log('Created a new council: ' . $council->name);

            return redirect()
                ->route('adviser.organizations.show', $council->id)
                ->with('success', 'Organization created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create organization: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $adviser = Auth::guard('adviser')->user();

        $council = Council::with([
            'department',
            'councilPositions.position',
            'councilPositions.student'
        ])
        ->where('adviser_id', $adviser->id)
        ->findOrFail($id);

        return view('adviser.organizations.show', compact('council'));
    }

    public function edit($id)
    {
        $adviser = Auth::guard('adviser')->user();

        $council = Council::where('adviser_id', $adviser->id)->findOrFail($id);
        $academicYears = [
            (date('Y') - 1) . ' - ' . date('Y'),
            date('Y') . ' - ' . (date('Y') + 1),
            (date('Y') + 1) . ' - ' . (date('Y') + 2),
        ];

        return view('adviser.organizations.edit', compact('council', 'academicYears'));
    }

    public function update(Request $request, $id)
    {
        $adviser = Auth::guard('adviser')->user();

        $council = Council::where('adviser_id', $adviser->id)->findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
        ]);

        // Ensure adviser_id doesn't change
        $data = $request->all();
        $data['adviser_id'] = $adviser->id;

        try {
            $updatedCouncil = $this->councilService->updateCouncil($council, $data);

            activity()
                ->causedBy($adviser)
                ->performedOn($council)
                ->log('Updated council: ' . $council->name);

            return redirect()
                ->route('adviser.organizations.show', $council->id)
                ->with('success', 'Organization updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update organization: ' . $e->getMessage());
        }
    }

    public function assignPositions($id)
    {
        $adviser = Auth::guard('adviser')->user();

        $council = Council::with(['councilPositions.position', 'councilPositions.student'])
            ->where('adviser_id', $adviser->id)
            ->findOrFail($id);

        // Get eligible students (from same department for departmental councils)
        $query = Student::query();

        if ($council->department_id) {
            $query->where('department_name', $council->department->name);
        }

        // Exclude students already in a council for this academic year
        $excludedStudentIds = CouncilPosition::whereHas('council', function ($q) use ($council) {
            $q->where('academic_year', $council->academic_year);
        })->whereNotNull('student_id')->pluck('student_id')->toArray();

        if (!empty($excludedStudentIds)) {
            $query->whereNotIn('id', $excludedStudentIds);
        }

        $eligibleStudents = $query->get();

        return view('adviser.organizations.assign-positions', compact('council', 'eligibleStudents'));
    }

    public function storePositions(Request $request, $id)
    {
        $adviser = Auth::guard('adviser')->user();

        $council = Council::where('adviser_id', $adviser->id)->findOrFail($id);

        $this->validate($request, [
            'positions' => 'required|array',
            'positions.*' => 'nullable|exists:students,id',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->positions as $councilPositionId => $studentId) {
                $councilPosition = CouncilPosition::where('council_id', $council->id)
                    ->findOrFail($councilPositionId);

                // Skip if no change or if trying to assign null
                if (($councilPosition->student_id == $studentId) ||
                    (empty($studentId) && is_null($councilPosition->student_id))) {
                    continue;
                }

                // Remove current student if any
                if ($councilPosition->student_id) {
                    $this->councilService->removeStudentFromPosition($councilPosition);
                }

                // Assign new student if provided
                if (!empty($studentId)) {
                    $this->councilService->assignStudentToPosition(
                        $council,
                        $councilPosition->position_id,
                        $studentId
                    );
                }
            }

            DB::commit();

            activity()
                ->causedBy($adviser)
                ->performedOn($council)
                ->log('Updated student positions for council: ' . $council->name);

            return redirect()
                ->route('adviser.organizations.show', $council->id)
                ->with('success', 'Positions assigned successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign positions: ' . $e->getMessage());
        }
    }
}
