<?php

namespace Routes;

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::prefix('/mail')->group(function () {
    Route::get('/send', [MailController::class, 'showTasks']);
});
