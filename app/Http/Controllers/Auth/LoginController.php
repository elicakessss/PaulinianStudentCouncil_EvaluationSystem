<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Administrator;
use App\Models\Adviser;
use App\Models\Student;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
public function showLoginForm()
{
    // Force logout of all guards
    Auth::guard('admin')->logout();
    Auth::guard('adviser')->logout();
    Auth::guard('student')->logout();

    // Clear session
    session()->invalidate();
    session()->regenerateToken();

    return view('auth.login');
}

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'id_number' => 'required',
            'password' => 'required',
            'role' => 'required|in:admin,adviser,student',
        ]);

        $credentials = [
            'id_number' => $request->id_number,
            'password' => $request->password,
        ];

        $guard = $request->role;

        if (Auth::guard($guard)->attempt($credentials, $request->has('remember'))) {
            // Clear session and regenerate token for security
            $request->session()->regenerate();

            // Log successful login
            // Activity::create([
            //     'log_name' => 'default',
            //     'description' => 'User logged in',
            //     'subject_type' => get_class(Auth::guard($guard)->user()),
            //     'subject_id' => Auth::guard($guard)->user()->id,
            //     'causer_type' => get_class(Auth::guard($guard)->user()),
            //     'causer_id' => Auth::guard($guard)->user()->id,
            // ]);

            // Redirect based on role
            if ($guard === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($guard === 'adviser') {
                return redirect()->intended(route('adviser.dashboard'));
            } else {
                return redirect()->intended(route('student.dashboard'));
            }
        }

        return back()
            ->withInput($request->only('id_number', 'role', 'remember'))
            ->withErrors([
                'credentials' => 'The provided credentials do not match our records.',
            ]);
    }
}
