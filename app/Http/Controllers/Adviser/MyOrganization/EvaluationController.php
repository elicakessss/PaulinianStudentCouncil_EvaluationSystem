<?php

namespace App\Http\Controllers\Adviser\MyOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return view('adviser.my_organization.evaluation.index');
    }
}
