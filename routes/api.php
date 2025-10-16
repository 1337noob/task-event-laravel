<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/{task}', [TaskController::class, 'show'])
        ->middleware('can:view,task')
        ->name('tasks.show');
    Route::put('/{task}', [TaskController::class, 'update'])
        ->middleware('can:update,task')
        ->name('tasks.update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])
        ->middleware('can:delete,task')
        ->name('tasks.delete');
});
