<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function build()
    {
        Log::info("TaskReminderMail triggered for: " . $this->task->title);
        return $this->subject('Task Reminder: Due Tomorrow')
            ->markdown('emails.task-reminder', [
                'task' => $this->task
            ]);
    }
}
