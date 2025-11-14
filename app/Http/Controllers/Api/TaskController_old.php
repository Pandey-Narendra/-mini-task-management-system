<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * LIST TASKS (Paginated + Cached)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $page = $request->get('page', 1);
        $cacheKey = "tasks_user_{$userId}_page_{$page}";

        $tasks = Cache::remember($cacheKey, 60, function () use ($userId) {
            return Task::select('id','title','status','due_date')
                ->where('user_id', $userId)
                ->latest()
                ->paginate(10);
        });

        return response()->json([
            'success' => true,
            'data'    => $tasks
        ]);
    }

    /**
     * CREATE TASK
     */
    public function store(Request $request)
    {

    
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in-progress,completed',
            'due_date'    => 'required|date|after:yesterday',
        ]);

        $task = Task::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        Cache::forget("tasks_user_" . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data'    => $task
        ], 201);
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

        Cache::forget("tasks_user_" . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data'    => $task
        ]);
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

        Cache::forget("tasks_user_" . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }
}
