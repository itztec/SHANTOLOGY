<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the clinician login & landing portal.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($credentials['login']);
        $password = $credentials['password'];

        // Allow login using username ('admin') or email ('admin@shantology.com')
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('status', 'Welcome back, '.$user->name.'!');
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => 'Invalid Clinician ID or Password. Default ID is "admin" with password "1234".',
            ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'You have been safely logged out of the SHANTOLOGY Clinical Portal.');
    }
}
