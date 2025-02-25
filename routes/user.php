<?php

namespace Routes;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::get('/users/{id}/tasks', [UserController::class, 'showTasks']);
    Route::get('/users/{id}/confirm', [UserController::class, 'confirmEmail']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
