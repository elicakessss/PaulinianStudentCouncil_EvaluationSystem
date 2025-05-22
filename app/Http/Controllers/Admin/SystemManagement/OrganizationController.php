<?php

namespace App\Http\Controllers\Admin\SystemManagement;

use App\Http\Controllers\Controller;
use App\Models\Council;
use App\Models\Department;
use App\Models\Adviser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    /**
     * Display all councils in the system (system-wide oversight)
     */
    public function index(Request $request)
    {
        $query = Council::with(['adviser', 'department', 'councilPositions']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            if ($request->department_id === 'university_wide') {
                $query->whereNull('department_id');
            } else {
                $query->where('department_id', $request->department_id);
            }
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $councils = $query->orderBy('created_at', 'desc')->paginate(15);
        $departments = Department::orderBy('name')->get();
        $academicYears = Council::distinct()->pluck('academic_year')->sort()->values();

        // Calculate statistics
        $stats = [
            'total_councils' => Council::count(),
            'active_councils' => Council::where('status', 'active')->count(),
            'university_wide_councils' => Council::whereNull('department_id')->count(),
            'department_councils' => Council::whereNotNull('department_id')->count(),
            'deactivated_councils' => Council::where('status', 'deactivated')->count(),
        ];

        return view('admin.system_management.organizations.index', compact(
            'councils',
            'departments',
            'academicYears',
            'stats'
        ));
    }

    /**
     * Display the specified council (system oversight view)
     */
    public function show($id)
    {
        $council = Council::with([
            'adviser',
            'department',
            'councilPositions.position',
            'councilPositions.student'
        ])->findOrFail($id);

        return view('admin.system_management.organizations.show', compact('council'));
    }

    /**
     * Activate a deactivated council
     */
    public function activate($id)
    {
        try {
            $council = Council::findOrFail($id);
            $council->update(['status' => 'active']);

            Log::info('Council activated by system admin', [
                'council_id' => $council->id,
                'admin_id' => auth()->id()
            ]);

            return back()->with('success', 'Council activated successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to activate council', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to activate council. Please try again.');
        }
    }

    /**
     * Deactivate a council (system oversight)
     */
    public function destroy($id)
    {
        try {
            $council = Council::findOrFail($id);

            // Instead of deleting, deactivate the council
            $council->update(['status' => 'deactivated']);

            Log::info('Council deactivated by system admin', [
                'council_id' => $council->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.system_management.organizations.index')
                ->with('success', 'Council deactivated successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to deactivate council', ['error' => $e->getMessage()]);

            return back()->with('error', 'Failed to deactivate council. Please try again.');
        }
    }

    /**
     * Get system statistics for councils
     */
    public function getStats()
    {
        return [
            'total_councils' => Council::count(),
            'active_councils' => Council::where('status', 'active')->count(),
            'university_wide' => Council::whereNull('department_id')->count(),
            'department_specific' => Council::whereNotNull('department_id')->count(),
            'by_department' => Council::with('department')
                ->whereNotNull('department_id')
                ->get()
                ->groupBy('department.name')
                ->map(function ($councils) {
                    return $councils->count();
                }),
            'by_academic_year' => Council::selectRaw('academic_year, count(*) as count')
                ->groupBy('academic_year')
                ->orderBy('academic_year', 'desc')
                ->pluck('count', 'academic_year'),
        ];
    }
}
