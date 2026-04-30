<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function show_login() {

        return view('auth.Login');
    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $key = 'login_'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Trop de tentatives. Réessayez dans 1 minute.'
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {

            RateLimiter::clear($key);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.show'));
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect',
        ])->withInput();
    }

    public function logout () {
        
        Auth::logout();
        return redirect()->route("login.show");
    }
}

