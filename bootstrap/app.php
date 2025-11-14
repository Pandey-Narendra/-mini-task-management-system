<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // API Middleware Group (Sanctum + Throttle + Model Binding)
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })

    ->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->job(new \App\Jobs\SendTaskReminderJob())
            ->dailyAt('09:00') 
            ->onFailure(function ($e) {
                \Log::error('Task Reminder Scheduler failed: '.$e->getMessage());
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom API Exception formatting may be added later
    })

    ->create();
