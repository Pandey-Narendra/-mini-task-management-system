<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * LIST TASKS (Web)
     */
    public function index()
    {
        $tasks = Task::select('id', 'title', 'status', 'due_date')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * SHOW CREATE FORM
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * STORE NEW TASK
     */
    public function store(Request $request)
    {

        // dd($request);
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in-progress,completed',
            'due_date'    => 'required|date|after:yesterday',
        ]);

        //  dd($request);

        Task::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * SHOW EDIT FORM
     */
    public function edit($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tasks.edit', compact('task'));
    }

    /**
     * UPDATE TASK
     */
    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in-progress,completed',
            'due_date'    => 'required|date|after:yesterday',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * DELETE TASK
     */
    public function destroy($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
