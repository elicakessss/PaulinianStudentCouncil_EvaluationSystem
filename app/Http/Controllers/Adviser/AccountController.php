<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use App\Models\Adviser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display the account management page
     */
    public function index()
    {
        $user = Auth::guard('adviser')->user();
        return view('adviser.account.index', compact('user'));
    }

    /**
     * Show the form for editing account
     */
    public function edit()
    {
        $user = Auth::guard('adviser')->user();
        return view('adviser.account.edit', compact('user'));
    }

    /**
     * Update the adviser's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()->route('adviser.account')->with('success', 'Account updated successfully');
    }

    /**
     * Update the adviser's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:6|confirmed|regex:/^[0-9]+$/',
        ]);

        // Check if current password matches
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('adviser.account')->with('success', 'Password updated successfully');
    }

    /**
     * Update the adviser's profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $validated = $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store the new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        $user->profile_picture = $path;
        $user->save();

        return redirect()->route('adviser.account')->with('success', 'Profile picture updated successfully');
    }
}
