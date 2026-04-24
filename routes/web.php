<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.Login');
})->name('Login');

Route::get('/forgetpassword', function () {
    return view('auth.Forgetpassword');
})->name('ForgetPassword');


Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('Dashboard');
