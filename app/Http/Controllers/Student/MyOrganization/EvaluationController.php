<?php

namespace App\Http\Controllers\Student\MyOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display evaluations for councils the student is part of
     */
    public function index()
    {
        return view('student.my_organization.evaluation.index');
    }
}
