<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/register', [Controllers\AuthController::class, 'register'])->name('register');
