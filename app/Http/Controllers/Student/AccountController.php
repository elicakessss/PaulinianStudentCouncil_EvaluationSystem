<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\AccountManagement;

class AccountController extends Controller
{
    use AccountManagement;

    /**
     * Show the student account page
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        $profilePictureUrl = $this->getProfilePictureUrl($student->profile_picture);

        return view('student.account.index', compact('student', 'profilePictureUrl'));
    }

    /**
     * Update the student's account information
     */
    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student->first_name = $validated['first_name'];
        $student->last_name = $validated['last_name'];
        $student->description = $validated['description'];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $student->profile_picture = $this->handleProfilePicture($request, $student);
        }

        $student->save();

        return redirect()->route('student.account')->with('success', 'Account information updated successfully.');
    }

    /**
     * Update the student's password
     */
    public function updatePassword(Request $request)
    {
        $student = Auth::guard('student')->user();

        $this->validatePasswordUpdate($request);

        // Check if current password matches
        if (!$this->checkCurrentPassword($student, $request->current_password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $student->password = Hash::make($request->password);
        $student->save();

        return redirect()->route('student.account')->with('success', 'Password updated successfully.');
    }
}
