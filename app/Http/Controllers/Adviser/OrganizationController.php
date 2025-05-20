<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('adviser.organization.index');
    }

    public function create()
    {
        return view('adviser.organization.create');
    }

}
