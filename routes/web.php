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

    // Appointments
    Route::get('/appointments',        fn() => view('appointments.index'))->name('appointments.index');
    Route::get('/appointments/create', fn() => view('appointments.create'))->name('appointments.create');
    Route::post('/appointments',       fn() => redirect('/appointments'))->name('appointments.store');

    // Patients
    Route::get('/patients', [PatientsController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientsController::class, 'create'])->name('patients.create');
    
    // Staff
    Route::get('/staff',        fn() => view('staff.index'))->name('staff.index');
    Route::get('/staff/create', fn() => view('staff.create'))->name('staff.create');
    Route::post('/staff',       fn() => redirect('/staff'))->name('staff.store');

    // Queue
    Route::get('/queue',        fn() => view('queue.index'))->name('queue.index');
    Route::get('/queue/create', fn() => view('queue.create'))->name('queue.create');
    Route::post('/queue',       fn() => redirect('/queue'))->name('queue.store');

    // Consultations
    Route::get('/consultations',        fn() => view('consultations.index'))->name('consultations.index');
    Route::get('/consultations/create', fn() => view('consultations.create'))->name('consultations.create');
    Route::post('/consultations',       fn() => redirect('/consultations'))->name('consultations.store');

    // Prescriptions
    Route::get('/prescriptions',        fn() => view('prescriptions.index'))->name('prescriptions.index');
    Route::get('/prescriptions/create', fn() => view('prescriptions.create'))->name('prescriptions.create');
    Route::post('/prescriptions',       fn() => redirect('/prescriptions'))->name('prescriptions.store');

    // Billing
    Route::get('/billing',        fn() => view('billing.index'))->name('billing.index');
    Route::get('/billing/create', fn() => view('billing.create'))->name('billing.create');
    Route::post('/billing',       fn() => redirect('/billing'))->name('billing.store');

    // Account
    Route::get('/account', fn() => view('account.index'))->name('account.index');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
