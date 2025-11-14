<x-mail::message>

# 🔔 Task Due Tomorrow Reminder

Hi {{ $user->name ?? 'User' }},

This is a reminder that your task is due tomorrow.

---

## 📝 Task Details

**Title:** {{ $task->title }}

**Description:**  
{{ $task->description ?? 'No description provided.' }}

**Status:**

@php
    $color = match ($task->status) {
        'completed' => 'green',
        'in-progress' => 'orange',
        default => 'red',
    };
@endphp

<p style="padding:6px 12px; background: {{ $color }}; color:white; border-radius:4px; display:inline-block;">
    {{ ucfirst($task->status) }}
</p>

**Due Date:**  
{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}

---

<x-mail::button :url="url('/tasks/'.$task->id.'/edit')">
    View / Update Task
</x-mail::button>

Thanks,  
{{ config('app.name') }}

</x-mail::message>
