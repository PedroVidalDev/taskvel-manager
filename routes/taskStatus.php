<?php

namespace Routes;

use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::prefix('/taskStatus')->group(function () {
    Route::put('/{id}', [TaskStatusController::class, 'update']);
    Route::delete('/{id}', [TaskStatusController::class, 'destroy']);
    Route::post('/', [TaskStatusController::class, 'store']);
});
