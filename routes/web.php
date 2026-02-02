<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::prefix('/users')
    ->name('users.')
    ->group(function () {
        Route::get('/@{username}', [Controllers\ProfileController::class, 'profile'])->name('profile');
    });

Route::prefix('/religions')
    ->name('religions.')
    ->controller(Controllers\ReligionController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{religion}', 'show')->name('show');
        Route::get('/{religion}/doctrines', 'doctrines')->name('doctrines');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::post('/', 'store')->name('store');
            Route::put('/{religion}', 'update')->name('update');
        });
    });

Route::prefix('/doctrines')
    ->name('doctrines.')
    ->controller(Controllers\DoctrineController::class)
    ->group(function () {
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::post('/', 'store')->name('store');
        });
    });

require __DIR__ . '/auth.php';
require __DIR__.'/settings.php';
