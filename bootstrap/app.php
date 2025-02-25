<?php

namespace Bootstrap;

use App\Jobs\MailJob;
use App\Models\Scopes\ActiveScope;
use App\Models\Task;
use App\Models\User;
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
    ->withSchedule(function ($schedule) {
        $schedule->call(function () {
            $tasks = Task::where('due_date', now()->toDateString())
                ->where('status', '!=', 'FINISHED')
                ->get();

            foreach ($tasks as $task) {
                dispatch(new MailJob([
                    'email' => $task->user->email,
                    'title' => "Taskvel | Tarefa com limite para hoje!",
                    'text' => "A tarefa {$task->title} tem limite para hoje! Não se esqueça"
                ]));
            }
        })->daily();
    })
    ->withSchedule(function ($schedule) {
        $schedule->call(function () {
            $users = User::withoutGlobalScope(ActiveScope::class)->where('is_active', false)->get();

            foreach ($users as $user) {
                User::withoutGlobalScope(ActiveScope::class)->destroy($user->id);
            }
        })->weekly();
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
