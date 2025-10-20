<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])
        ->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('auth.login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
        Route::get('/me', [AuthController::class, 'me'])
            ->name('auth.me');
    });
});

Route::prefix('tasks')->middleware('auth:api')->group(function () {
    Route::get('/', [TaskController::class, 'index'])
        ->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])
        ->name('tasks.create');
    Route::get('/{id}', [TaskController::class, 'show'])
        ->name('tasks.show');
    Route::put('/{id}', [TaskController::class, 'update'])
        ->name('tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])
        ->name('tasks.delete');
});

Route::get('/logs', [LogController::class, 'index'])
    ->name('logs.index');
