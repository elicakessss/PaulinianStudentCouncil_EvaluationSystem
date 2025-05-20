<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait AccountManagement
{
    /**
     * Handle profile picture upload
     */
    protected function handleProfilePicture(Request $request, $user)
    {
        if ($request->hasFile('profile_picture')) {
            // Delete previous profile picture if it exists and is not the default
            if ($user->profile_picture && $user->profile_picture != 'default-profile.png') {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            return $path;
        }

        // No new profile picture uploaded, keep existing one
        return $user->profile_picture;
    }

    /**
     * Validate password update request
     */
    protected function validatePasswordUpdate(Request $request)
    {
        return $request->validate([
            'current_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Check if current password matches
     */
    protected function checkCurrentPassword($user, $currentPassword)
    {
        return Hash::check($currentPassword, $user->password);
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePictureUrl($profilePicture)
    {
        if (!$profilePicture) {
            return asset('images/default-profile.png');
        }

        if (Str::startsWith($profilePicture, 'http')) {
            return $profilePicture;
        }

        return asset('storage/' . $profilePicture);
    }
}
