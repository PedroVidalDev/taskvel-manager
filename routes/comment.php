<?php

namespace Routes;

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::put('/{id}', [CommentController::class, 'update']);
    Route::delete('/{id}', [CommentController::class, 'destroy']);
    Route::post('/', [CommentController::class, 'store']);
});
