<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/religions')
    ->name('api.religions.')
    ->controller(API\ReligionController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
        Route::get('/{religion}', 'show')->name('show');
    });

Route::prefix('/doctrines')
    ->name('api.doctrines.')
    ->controller(API\DoctrineController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
        Route::get('/{doctrine}', 'show')->name('show');
    });

Route::prefix('/faiths')
    ->name('api.faiths.')
    ->controller(API\FaithController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
        Route::get('/{faith}', 'show')->name('show');
    });

Route::prefix('/tags')
    ->name('api.tags.')
    ->controller(API\TagController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
        Route::get('/{tag}', 'show')->name('show');
    });

Route::prefix('/correlations')
    ->name('api.correlations.')
    ->controller(API\CorrelationController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
    });

Route::prefix('/votes')
    ->name('api.votes.')
    ->controller(API\VoteController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::put('/update', 'update')->name('update');
                Route::delete('/delete', 'delete')->name('delete');
            });

        Route::get('/', 'index')->name('index');
    });
