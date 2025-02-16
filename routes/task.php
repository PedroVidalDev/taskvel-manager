<?php

namespace Routes;

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('/task')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::post('/store', [TaskController::class, 'store']);
    Route::post('/{id}', [TaskController::class, 'update']);
    Route::get('/{id}', [TaskController::class, 'destroy']);
});
