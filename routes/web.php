<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'Bem vindo ao Comerc Pastry!'];
});
