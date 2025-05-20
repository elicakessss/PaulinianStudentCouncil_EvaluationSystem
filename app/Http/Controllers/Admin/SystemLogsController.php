<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SystemLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query();

        // Apply filters
        if ($request->has('causer_type') && !empty($request->causer_type)) {
            $query->where('causer_type', $request->causer_type);
        }

        if ($request->has('causer_id') && !empty($request->causer_id)) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->has('subject_type') && !empty($request->subject_type)) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::findOrFail($id);

        return view('admin.logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = Activity::query();

        // Apply all filters from the request
        if ($request->has('causer_type') && !empty($request->causer_type)) {
            $query->where('causer_type', $request->causer_type);
        }

        if ($request->has('causer_id') && !empty($request->causer_id)) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->has('subject_type') && !empty($request->subject_type)) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get logs in chronological order for the export
        $logs = $query->orderBy('created_at', 'asc')->get();

        $csvHeaders = [
            'ID',
            'Log Name',
            'Description',
            'Subject Type',
            'Subject ID',
            'Causer Type',
            'Causer ID',
            'Causer Name',
            'Created At'
        ];

        $csvData = [];

        foreach ($logs as $log) {
            $causerName = '';

            // Try to get the causer name if it exists
            if ($log->causer) {
                $causerName = $log->causer->first_name . ' ' . $log->causer->last_name;
            }

            $csvData[] = [
                $log->id,
                $log->log_name,
                $log->description,
                $log->subject_type,
                $log->subject_id,
                $log->causer_type,
                $log->causer_id,
                $causerName,
                $log->created_at->format('Y-m-d H:i:s')
            ];
        }

        // Generate a filename with the current date
        $filename = 'system_logs_' . date('Y-m-d') . '.csv';

        // Create and return the CSV file
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add headers
        fputcsv($handle, $csvHeaders);

        // Add rows
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }
}
