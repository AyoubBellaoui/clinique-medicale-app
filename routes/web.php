<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffMedicalController;
use App\Http\Controllers\FileAttenteController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\SearchController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'show_login'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Auth Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'show_dashboard'])->name('dashboard.show');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    // Staff Medical
    Route::get('/staff', [StaffMedicalController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffMedicalController::class, 'create'])->name('staff.create');
    Route::post('/staff/store', [StaffMedicalController::class, 'store'])->name('staff.store');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
