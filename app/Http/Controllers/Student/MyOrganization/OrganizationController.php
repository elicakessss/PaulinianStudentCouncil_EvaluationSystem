<?php

namespace App\Http\Controllers\Student\MyOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display councils that the student is a member of
     */
    public function index()
    {
        return view('student.my_organization.organization.index');
    }
}
