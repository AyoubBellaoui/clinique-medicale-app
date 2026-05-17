<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffMedicalController;
use App\Http\Controllers\FileAttenteController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\OrdonnancesController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\FacturesController;
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
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}/update', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/delete/{id}', [PatientController::class, 'destroy'])->name('patients.delete');

    // Staff Medical
    Route::get('/staff', [StaffMedicalController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffMedicalController::class, 'create'])->name('staff.create');
    Route::post('/staff/store', [StaffMedicalController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [StaffMedicalController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}/update', [StaffMedicalController::class, 'update'])->name('staff.update');
    Route::delete('/Staff/delete/{id}', [StaffMedicalController::class, 'destroy'])->name('staff.delete');

    // File d'attente
    Route::get('/fileAttente', [FileAttenteController::class, 'index'])->name('fileAttente.index');
    Route::get('/fileAttente/create', [FileAttenteController::class, 'create'])->name('fileAttente.create');

    // Rendez-vous
    Route::get('/appointments', [RendezVousController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [RendezVousController::class, 'create'])->name('appointments.create');

    // Consultations
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');

    // Ordonnances
    Route::get('/prescriptions', [OrdonnancesController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/create', [OrdonnancesController::class, 'create'])->name('prescriptions.create');

    // Factures
    Route::get('/billing', [FacturesController::class, 'index'])->name('billing.index');
    Route::get('/billing/create', [FacturesController::class, 'create'])->name('billing.create');

    // Mon Compte
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
