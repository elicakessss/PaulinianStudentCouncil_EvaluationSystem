<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Adviser;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::guard('adviser')->user();

        return view('adviser.account.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('advisers')->ignore($user->id),
            ],
            'id_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('advisers')->ignore($user->id),
            ],
            'description' => 'nullable|string',
        ]);

        try {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->id_number = $request->id_number;
            $user->description = $request->description;
            $user->save();

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('Updated account profile');

            return redirect()
                ->route('adviser.account')
                ->with('success', 'Account updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update account: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('Changed account password');

            return redirect()
                ->route('adviser.account')
                ->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $user = Auth::guard('adviser')->user();

        $this->validate($request, [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Delete old profile picture if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            $user->profile_picture = $path;
            $user->save();

            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('Updated profile picture');

            return redirect()
                ->route('adviser.account')
                ->with('success', 'Profile picture updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile picture: ' . $e->getMessage());
        }
    }
}
