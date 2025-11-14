<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Support\Facades\Log;
use App\Jobs\SendTaskDueTomorrowReminderJob;

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

   ->withSchedule(function (Schedule $schedule) {
        $schedule->job(new SendTaskDueTomorrowReminderJob())
            ->dailyAt('09:00')
            ->before(function () {
                Log::info("Scheduler started: Checking for tomorrow's tasks.");
            })
            ->onSuccess(function () {
                Log::info("Scheduler executed successfully.");
            })
            ->onFailure(function (\Throwable $e) {
                Log::error('Scheduler failed: '.$e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
            });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom API Exception formatting may be added later
    })

    ->create();
