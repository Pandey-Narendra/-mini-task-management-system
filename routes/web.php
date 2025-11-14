<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\TaskController;
// use App\Mail\TaskReminderMail;
use App\emails\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/test-mail', function () {
//     try {
//         $task = new \App\Models\Task([
//             'title' => 'Demo Task',
//             'due_date' => now()->addDay(),
//         ]);

//         Mail::to('test@example.com')->send(new TaskReminderMail($task));

//         return "Mail sent successfully. Check storage/logs/laravel.log";
//     } catch (\Exception $e) {
//         Log::error('Mail sending failed: ' . $e->getMessage());
//         return "Mail failed: " . $e->getMessage();
//     }
// });

// Route::get('/test-mail', function () {
//     \Mail::raw('Testing Gmail SMTP from Laravel', function ($message) {
//         $message->to('test@gmail.com')
//                 ->subject('SMTP Test');
//     });

//     return "Email sent!";
// });


// Route::get('/test-queued-mail', function () {
//     Mail::to('test@gmail.com')->queue(new App\Mail\TaskReminderMail(\App\Models\Task::first()));

//     return "Queued mail triggered!";
// });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // task
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';
