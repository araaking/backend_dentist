<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController as ApiPatientController;
use App\Http\Controllers\Api\ConsultationController as ApiConsultationController;
use App\Http\Controllers\Api\QuestionsController as ApiQuestionsController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Questions metadata for Flutter
    Route::get('questions', [ApiQuestionsController::class, 'index']);

    // Patient profile
    Route::get('patient', [ApiPatientController::class, 'show']);
    Route::post('patient', [ApiPatientController::class, 'store']);
    Route::put('patient', [ApiPatientController::class, 'update']);

    // Consultations
    Route::get('consultations', [ApiConsultationController::class, 'index']);
    Route::post('consultations', [ApiConsultationController::class, 'store']);
    Route::get('consultations/{consultation}', [ApiConsultationController::class, 'show']);
    Route::get('consultations/{consultation}/photo', [ApiConsultationController::class, 'photo'])
        ->name('api.consultations.photo');

    // Hapus akun (wajib password)
    Route::delete('account', [AuthController::class, 'destroyAccount'])->middleware('throttle:sensitive');
});
