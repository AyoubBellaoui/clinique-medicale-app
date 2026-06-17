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
use App\Models\FileAttente;

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
    Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
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
    Route::post('/fileAttente/store', [FileAttenteController::class, 'store'])->name('FileAttente.store');
    Route::get('/fileAttente/{id}/edit', [FileAttenteController::class, 'edit'])->name('FileAttente.edit');
    Route::put('/fileAttente/{id}/update', [FileAttenteController::class, 'update'])->name('FileAttente.update');
    Route::delete('/fileAttente/delete/{id}', [FileAttenteController::class, 'destroy'])->name('FileAttente.delete');

    // Rendez-vous
    Route::get('/appointments', [RendezVousController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [RendezVousController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/store', [RendezVousController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [RendezVousController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}/update', [RendezVousController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/delete/{id}', [RendezVousController::class, 'destroy'])->name('appointments.delete');

    // Consultations
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/consultations/store', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{id}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
    Route::put('/consultations/{id}/update', [ConsultationController::class, 'update'])->name('consultations.update');
    Route::delete('/consultations/delete/{id}', [ConsultationController::class, 'destroy'])->name('consultations.delete');

    // Ordonnances
    Route::get('/prescriptions', [OrdonnancesController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/create', [OrdonnancesController::class, 'create'])->name('prescriptions.create');
    Route::post('/prescriptions/store', [OrdonnancesController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{id}/edit', [OrdonnancesController::class, 'edit'])->name('prescriptions.edit');
    Route::put('/prescriptions/{id}/update', [OrdonnancesController::class, 'update'])->name('prescriptions.update');
    Route::delete('/prescriptions/delete/{id}', [OrdonnancesController::class, 'destroy'])->name('prescriptions.delete');

    // Factures
    Route::get('/billing', [FacturesController::class, 'index'])->name('billing.index');
    Route::get('/billing/create', [FacturesController::class, 'create'])->name('billing.create');
    Route::post('/billing/store', [FacturesController::class, 'store'])->name('billing.store');
    Route::get('/billing/{id}/edit', [FacturesController::class, 'edit'])->name('billing.edit');
    Route::put('/billing/{id}/update', [FacturesController::class, 'update'])->name('billing.update');
    Route::delete('/billing/delete/{id}', [FacturesController::class, 'destroy'])->name('billing.delete');

    // Mon Compte
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
