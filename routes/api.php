<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{LoginController, RegisterController};

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
