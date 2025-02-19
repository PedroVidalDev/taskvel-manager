<?php

namespace Bootstrap;

use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $exception, \Illuminate\Http\Request $request) {
            if ($request->is('*')) {
                return response()->json([
                    'status' => '409',
                    'message' => $exception->errors(),
                ], 409);
            }
        });

        $exceptions->render(function (EntityNotFoundException $exception, \Illuminate\Http\Request $request) {
            if ($request->is('*')) {
                return response()->json([
                    'status' => '404',
                    'message' => $exception->getMessage(),
                ], 409);
            }
        });

        $exceptions->render(function (JWTException $exception, \Illuminate\Http\Request $request) {
            if ($request->is('*')) {
                return response()->json([
                    'status' => '403',
                    'message' => $exception->getMessage(),
                ], 409);
            }
        });
    })->create();
