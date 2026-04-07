<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Login');
})->name('Login');

Route::get('/Forgetpassword', function () {
    return view('/Forgetpassword');
})->name('ForgetPassword');



