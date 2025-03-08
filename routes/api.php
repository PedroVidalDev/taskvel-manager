<?php

namespace Routes;

use Illuminate\Support\Facades\Route;

Route::middleware("auth:api")->group(function () {
    require __DIR__ . '/task.php';
    require __DIR__ . '/comment.php';
});

Route::get('/health', function () {
    return response()->json([
        'message' => 'Im alive',
    ], 200);
});

require __DIR__ . '/user.php';
