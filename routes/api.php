<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use AliReaza\Laravel\Request\Middleware\FormData;
use App\Http\Controllers\{ClientController, ProductController};
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Auth\{RecoveryPasswordController, ResetPasswordController};

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
Route::post('password-recovery', RecoveryPasswordController::class)->name('password.recovery');
Route::put('password-reset', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('clients-list', [ClientController::class, 'listToSelect'])->name('clients.list');
    Route::apiResource('clients', ClientController::class);

    Route::get('products-list', [ProductController::class, 'listToSelect'])->name('products.list');
    Route::apiResource('products', ProductController::class)->middleware(FormData::class); // to resolve PUT error with form data;
    Route::apiResource('orders', OrderController::class);
});
