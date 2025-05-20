<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Determine which guard is currently authenticated
        $guard = null;

        if (Auth::guard('admin')->check()) {
            $guard = 'admin';
        } elseif (Auth::guard('adviser')->check()) {
            $guard = 'adviser';
        } elseif (Auth::guard('student')->check()) {
            $guard = 'student';
        }

        if ($guard) {
            Auth::guard($guard)->logout();
        }

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Clear any remember me cookies
        Cookie::queue(Cookie::forget('laravel_session'));

        return redirect()->route('login');
    }
}
