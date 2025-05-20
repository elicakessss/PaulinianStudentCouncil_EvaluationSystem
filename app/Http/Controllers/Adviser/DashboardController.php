<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Council;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $adviser = Auth::guard('adviser')->user();

        // Get organizations managed by this adviser
        $councils = Council::where('adviser_id', $adviser->id)
            ->with('department')
            ->get();

        // Count statistics
        $statistics = [
            'total_councils' => $councils->count(),
            'active_councils' => $councils->where('status', 'active')->count(),
            'students_assigned' => 0, // Will calculate below
        ];

        // Get assigned students and evaluation progress
        $councilsWithDetails = [];
        $studentIds = [];

        foreach ($councils as $council) {
            // Load council positions with students and positions
            $councilPositions = $council->councilPositions()
                ->with(['student', 'position'])
                ->get();

            // Count assigned students
            $assignedStudents = $councilPositions
                ->whereNotNull('student_id')
                ->pluck('student_id')
                ->unique();

            $studentIds = array_merge($studentIds, $assignedStudents->toArray());

            // Calculate evaluation progress (placeholder for now)
            // In the real implementation, calculate based on actual evaluation data
            $progress = rand(0, 100);

            $councilsWithDetails[] = [
                'council' => $council,
                'assigned_students' => $assignedStudents->count(),
                'available_positions' => $councilPositions->count(),
                'evaluation_progress' => $progress,
            ];
        }

        // Update total students count
        $statistics['students_assigned'] = count(array_unique($studentIds));

        // Get recent activities related to this adviser
        $recentActivities = activity()
            ->causedBy($adviser)
            ->orWhere(function ($query) use ($adviser) {
                $query->where('causer_type', 'App\Models\Adviser')
                    ->where('causer_id', $adviser->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('adviser.dashboard', compact(
            'statistics',
            'councilsWithDetails',
            'recentActivities'
        ));
    }
}
