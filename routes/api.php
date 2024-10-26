<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Auth\{RecoveryPasswordController, ResetPasswordController};

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
Route::post('password-recovery', RecoveryPasswordController::class)->name('password.recovery');
Route::put('password-reset', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
});
