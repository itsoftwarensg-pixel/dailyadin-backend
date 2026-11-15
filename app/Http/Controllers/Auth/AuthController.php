<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show the login form if the user is not authenticated,
     * otherwise redirect to the admin dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('adminDashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Login successful
            $user = Auth::user();

            // Optional: regenerate session to prevent fixation
            $request->session()->regenerate();

            if ($user->status == 1) { // or StatusEnum::ACTIVE if using enum
                return redirect()->intended(route('adminDashboard'));
            }

            // Logout if user status is inactive
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('loginForm')->with('error', 'Invalid user. Please contact your system admin.');
        }

        // Invalid credentials
        return redirect()->route('loginForm')->with('error', 'Login details are not valid');
    }

    /**
     * Log out the admin, clear the session, regenerate CSRF token, and redirect to login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Clear all session data
        Session::flush();

        // Logout the user
        Auth::logout();

        // Invalidate current session & regenerate CSRF token
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('loginForm')->with('message', 'You are logged out!');
    }
}
