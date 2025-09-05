<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

use App\Http\Controllers\Web\PatientController;
use App\Http\Controllers\Web\ConsultationController;

Route::get('/', function () {
    return view('welcome');
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

    Route::resource('consultations', ConsultationController::class)->only(['index', 'create']);
    Route::resource('patients', PatientController::class)->only(['edit', 'update']);
});
