<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

use App\Http\Controllers\Web\PatientController;
use App\Http\Controllers\Web\ConsultationController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('consultations', ConsultationController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('patients', PatientController::class)->only(['edit', 'update']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/patients', [App\Http\Controllers\Admin\PatientController::class, 'index'])->name('admin.patients.index');
    Route::get('/admin/patients/{patient}', [App\Http\Controllers\Admin\PatientController::class, 'show'])->name('admin.patients.show');
});
