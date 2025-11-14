<?php

namespace App\Jobs;

use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;
use Carbon\Carbon;

class SendTaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of tries before failing.
     */
    public int $tries = 3;

    /**
     * Retry after this many seconds.
     */
    public int $backoff = 10;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $tomorrow = Carbon::tomorrow()->toDateString();

            $tasks = Task::with('user')
                ->whereDate('due_date', $tomorrow)
                ->get();

            foreach ($tasks as $task) {

                // If user or email missing → skip safely
                if (!$task->user || !$task->user->email) {
                    Log::warning("Skipped task reminder: user/email missing", [
                        'task_id' => $task->id,
                    ]);
                    continue;
                }

                try {
                    Mail::to($task->user->email)
                        ->send(new TaskReminderMail($task));

                    Log::info("Reminder email sent", [
                        'task_id' => $task->id,
                        'email' => $task->user->email,
                    ]);

                } catch (Throwable $ex) {
                    // Log but DO NOT stop other users' mails
                    Log::error("Failed to send reminder email for task {$task->id}: " . $ex->getMessage());
                }
            }

        } catch (Throwable $e) {
            Log::error('Task Reminder Job Failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            throw $e; // Let Laravel retry
        }
    }

    /**
     * Handle job final failure.
     */
    public function failed(Throwable $e)
    {
        Log::critical('SendTaskReminderJob permanently failed after retries', [
            'exception' => $e->getMessage(),
        ]);
    }
}
