<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('admin.organization.index');
    }

    public function create()
    {
        return view('admin.organization.create');
    }

    public function store(Request $request)
    {
        // Placeholder
        return redirect()->route('admin.organization')
            ->with('success', 'Organization created successfully');
    }

    public function show($id)
    {
        return view('admin.organization.show');
    }
}
