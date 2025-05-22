<?php

namespace App\Http\Controllers\Admin\SystemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{
    public function index()
    {
        return view('admin.system_management.evaluation_report.index');
    }
}
