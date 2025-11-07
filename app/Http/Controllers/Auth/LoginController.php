<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        // Custom validation for login_credential (can be email or username)
        $request->validate([
            //            'email' => ['required', 'email'],
            'login_credential' => ['required'],
            'password' => ['required'],
        ], [
            'login_credential.required' => 'The email field is required.',
            'password.required' => 'The password field is required.',
        ]);

        //if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        // Determine if login_credential is an email
        // For now, we only support email login
        $credentials = [
            'email' => $request->login_credential,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'login_credential' => __('The provided credentials do not match our records.'),
           // 'email' => __('The provided credentials do not match our records.'),

        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
