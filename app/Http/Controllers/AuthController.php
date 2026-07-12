<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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

        // Two separate keys: the IP-keyed limiter stops one attacker from spraying
        // many accounts from a single machine, and the email-keyed limiter stops a
        // distributed attack (many IPs) from brute-forcing one specific account.
        $ipKey = 'login_ip_'.$request->ip();
        $emailKey = 'login_email_'.Str::lower($request->input('email'));

        if (RateLimiter::tooManyAttempts($ipKey, 5) || RateLimiter::tooManyAttempts($emailKey, 5)) {
            return back()->withErrors([
                'email' => 'Trop de tentatives. Réessayez dans 1 minute.'
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {

            RateLimiter::clear($ipKey);
            RateLimiter::clear($emailKey);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.show'));
        }

        RateLimiter::hit($ipKey, 60);
        RateLimiter::hit($emailKey, 60);

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect',
        ])->withInput();
    }

    public function logout () {
        
        Auth::logout();
        return redirect()->route("login.show");
    }
}

