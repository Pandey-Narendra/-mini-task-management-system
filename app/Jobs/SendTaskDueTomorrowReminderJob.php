<?php

namespace App\Jobs;

use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;  
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class SendTaskDueTomorrowReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            Log::info("Reminder job started.");

            $tomorrow = Carbon::tomorrow()->toDateString();

            $tasks = Task::with('user')
                ->whereDate('due_date', $tomorrow)
                ->get()
            ;

            Log::info("Tasks found for tomorrow: ".$tasks->count());

            if ($tasks->isEmpty()) {
                Log::warning("No tasks found for tomorrow.");
                return;
            }

            foreach ($tasks as $task) {

                if (!$task->user?->email) {
                    Log::warning("Task {$task->id} has no user email.");
                    continue;
                }

                Log::info("Queueing mail for task ID {$task->id} -> {$task->user->email}");

                Mail::to($task->user->email)
                    ->queue(new TaskReminderMail($task));
            }

            Log::info("Reminder job completed successfully.");

        } catch (\Throwable $e) {
            Log::error("Task Reminder Job Failed: ".$e->getMessage(), [
                'task_id' => $task->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; 
        }
    }

}
