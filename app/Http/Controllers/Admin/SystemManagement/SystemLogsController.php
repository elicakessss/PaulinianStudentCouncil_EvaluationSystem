<?php

namespace App\Http\Controllers\Admin\SystemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemLogsController extends Controller
{
    public function index()
    {
        // Simple placeholder until activity log package is installed
        $logs = collect(); // Empty collection for now

        return view('admin.system_management.system_logs.index', compact('logs'));
    }
}
