<?php

namespace Routes;

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('/projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::get('/{id}/tasks', [ProjectController::class, 'tasks']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
    Route::post('/', [ProjectController::class, 'store']);
});
