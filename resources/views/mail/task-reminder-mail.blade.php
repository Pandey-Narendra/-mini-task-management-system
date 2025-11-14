@component('mail::message')
# Task Reminder

Your task **"{{ $task->title }}"** is due tomorrow.

@component('mail::panel')
    Due Date: {{ $task->due_date->format('d M, Y') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
