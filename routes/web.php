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
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
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
    Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}/update', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/delete/{id}', [PatientController::class, 'destroy'])->name('patients.delete');

    // Staff Medical — HR-sensitive (salary/contract data), admin only
    Route::middleware('role')->group(function () {
        Route::get('/staff', [StaffMedicalController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffMedicalController::class, 'create'])->name('staff.create');
        Route::post('/staff/store', [StaffMedicalController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}/edit', [StaffMedicalController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{id}/update', [StaffMedicalController::class, 'update'])->name('staff.update');
        Route::delete('/Staff/delete/{id}', [StaffMedicalController::class, 'destroy'])->name('staff.delete');
    });

    // File d'attente
    Route::get('/fileAttente', [FileAttenteController::class, 'index'])->name('fileAttente.index');
    Route::get('/fileAttente/create', [FileAttenteController::class, 'create'])->name('fileAttente.create');
    Route::post('/fileAttente/store', [FileAttenteController::class, 'store'])->name('fileAttente.store');
    Route::get('/fileAttente/{id}/edit', [FileAttenteController::class, 'edit'])->name('fileAttente.edit');
    Route::put('/fileAttente/{id}/update', [FileAttenteController::class, 'update'])->name('fileAttente.update');
    Route::delete('/fileAttente/delete/{id}', [FileAttenteController::class, 'destroy'])->name('fileAttente.delete');

    // Rendez-vous
    Route::get('/appointments', [RendezVousController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [RendezVousController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/store', [RendezVousController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [RendezVousController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}/update', [RendezVousController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/delete/{id}', [RendezVousController::class, 'destroy'])->name('appointments.delete');

    // Consultations — medical records, clinical staff only
    Route::middleware('role:medical')->group(function () {
        Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
        Route::get('/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
        Route::post('/consultations/store', [ConsultationController::class, 'store'])->name('consultations.store');
        Route::get('/consultations/{id}/edit', [ConsultationController::class, 'edit'])->name('consultations.edit');
        Route::put('/consultations/{id}/update', [ConsultationController::class, 'update'])->name('consultations.update');
        Route::delete('/consultations/delete/{id}', [ConsultationController::class, 'destroy'])->name('consultations.delete');
    });

    // Ordonnances — prescriptions, clinical staff only
    Route::middleware('role:medical')->group(function () {
        Route::get('/prescriptions', [OrdonnancesController::class, 'index'])->name('prescriptions.index');
        Route::get('/prescriptions/create', [OrdonnancesController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions/store', [OrdonnancesController::class, 'store'])->name('prescriptions.store');
        Route::get('/prescriptions/{id}/edit', [OrdonnancesController::class, 'edit'])->name('prescriptions.edit');
        Route::put('/prescriptions/{id}/update', [OrdonnancesController::class, 'update'])->name('prescriptions.update');
        Route::delete('/prescriptions/delete/{id}', [OrdonnancesController::class, 'destroy'])->name('prescriptions.delete');
    });

    // Factures — billing, administrative/support staff only
    Route::middleware('role:support')->group(function () {
        Route::get('/billing', [FacturesController::class, 'index'])->name('billing.index');
        Route::get('/billing/create', [FacturesController::class, 'create'])->name('billing.create');
        Route::post('/billing/store', [FacturesController::class, 'store'])->name('billing.store');
        Route::get('/billing/{id}/edit', [FacturesController::class, 'edit'])->name('billing.edit');
        Route::put('/billing/{id}/update', [FacturesController::class, 'update'])->name('billing.update');
        Route::put('/billing/{id}/mark-paid', [FacturesController::class, 'markPaid'])->name('billing.markPaid');
        Route::delete('/billing/delete/{id}', [FacturesController::class, 'destroy'])->name('billing.delete');
    });

    // Mon Compte
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');

    // Utilisateurs — account/login management, admin only
    Route::middleware('role')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    });

    // Global header search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/count',    [NotificationController::class, 'unreadCount'])->name('count');
        Route::get('/',         [NotificationController::class, 'index'])->name('index');
        Route::post('/read-all',[NotificationController::class, 'markAllRead'])->name('readAll');
        Route::post('/{id}/read',[NotificationController::class, 'markRead'])->name('read');
    });

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
