<?php

namespace Routes;

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('/tasks')->group(function () {
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::get('/{id}/comments', [TaskController::class, 'showComments']);
    Route::get('/{id}/subtasks', [TaskController::class, 'getAllSubtasks']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
    Route::post('/', [TaskController::class, 'store']);
    Route::post('/{id}/subtasks', [TaskController::class, 'storeSubtask']);
});
