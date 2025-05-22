<?php

namespace App\Http\Controllers\Admin\MyOrganization;

use App\Http\Controllers\Controller;
use App\Models\Council;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Display evaluation progress for admin's university-wide councils
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // Get only university-wide councils (department_id is null)
        $councils = Council::with([
            'adviser',
            'councilPositions.student',
            'councilPositions.position'
        ])
        ->whereNull('department_id') // University-wide councils only
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate evaluation statistics
        $totalCouncils = $councils->count();
        $activeCouncils = $councils->where('status', 'active')->count();
        $completedEvaluations = 0; // This will be implemented when evaluation system is ready
        $pendingEvaluations = $totalCouncils - $completedEvaluations;

        return view('admin.my_organization.evaluation.index', compact(
            'councils',
            'totalCouncils',
            'activeCouncils',
            'completedEvaluations',
            'pendingEvaluations'
        ));
    }
}
