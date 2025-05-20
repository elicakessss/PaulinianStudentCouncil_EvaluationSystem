<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\Administrator;
use App\Models\Adviser;
use App\Models\Student;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the reset password email form
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'role' => 'required|in:admin,adviser,student',
        ]);

        $email = $request->email;
        $role = $request->role;

        // Check if user exists
        $user = null;

        if ($role === 'admin') {
            $user = Administrator::where('email', $email)->first();
        } elseif ($role === 'adviser') {
            $user = Adviser::where('email', $email)->first();
        } else {
            $user = Student::where('email', $email)->first();
        }

        if (!$user) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'We can\'t find a user with that email address for the selected role.']);
        }

        // Create a new token
        $token = Str::random(60);

        // Store the token in the password resets table
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
            'role' => $role,
        ]);

        // Get the reset password URL
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $email,
            'role' => $role,
        ], false));

        // Send the password reset email
        Mail::to($email)->send(new ResetPasswordMail($resetUrl, $user->first_name));

        return back()->with('status', 'We have emailed your password reset link!');
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email, 'role' => $request->role]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,adviser,student',
            'password' => 'required|confirmed|min:6',
        ]);

        $email = $request->email;
        $role = $request->role;
        $token = $request->token;

        // Verify token
        $resetRecord = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->where('role', $role)
            ->first();

        if (!$resetRecord) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Check if token has expired (1 hour)
        if (Carbon::parse($resetRecord->created_at)->addHour()->isPast()) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'This password reset token has expired.']);
        }

        // Update the user's password
        $user = null;

        if ($role === 'admin') {
            $user = Administrator::where('email', $email)->first();
        } elseif ($role === 'adviser') {
            $user = Adviser::where('email', $email)->first();
        } else {
            $user = Student::where('email', $email)->first();
        }

        if (!$user) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'We can\'t find a user with that email address for the selected role.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the password reset token
        DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->where('role', $role)
            ->delete();

        // Log the action
        activity()
            ->performedOn($user)
            ->log('Password was reset');

        // Redirect to login
        return redirect()
            ->route('login')
            ->with('status', 'Your password has been reset!');
    }
}
