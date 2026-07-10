<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);

        // The login page is the GET route "login.show" (POST "login" is the
        // form submission target); without this, the framework's default
        // guest redirect resolves the name "login" and sends guests to the
        // POST-only URL, which 405s instead of showing the login form.
        $middleware->redirectGuestsTo(fn () => route('login.show'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
