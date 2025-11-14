<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * STANDARD API RESPONSE FORMATTER
     */
    private function success($message, $data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    private function error($message, $errors = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    /**
     * LIST TASKS (Paginated + Cached)
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $page = (int) $request->input('page', 1);
            $cacheKey = "tasks_user_{$userId}_page_{$page}";

            $tasks = Cache::remember($cacheKey, 60, function () use ($userId) {
                return Task::select('id','title','status','due_date')
                    ->where('user_id', $userId)
                    ->orderBy('id', 'desc')
                    ->paginate(10);
            });

            return $this->success('Tasks fetched successfully.', $tasks);

        } catch (Exception $e) {
            Log::error('Task Fetch Error: ' . $e->getMessage());
            return $this->error('Something went wrong while fetching tasks.', null, 500);
        }
    }

    /**
     * CREATE TASK
     */
    public function store(Request $request)
    {
        try {
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

            // Invalidate all pages for that user
            Cache::tags(['tasks_user_' . Auth::id()])->flush();

            return $this->success('Task created successfully.', $task, 201);

        } catch (ValidationException $e) {
            return $this->error('Validation failed.', $e->errors(), 422);

        } catch (Exception $e) {
            Log::error('Task Creation Error: ' . $e->getMessage());
            return $this->error('Something went wrong while creating task.', null, 500);
        }
    }

    /**
     * UPDATE TASK
     */
    public function update(Request $request, $id)
    {
        try {
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

            Cache::tags(['tasks_user_' . Auth::id()])->flush();

            return $this->success('Task updated successfully.', $task);

        } catch (ModelNotFoundException $e) {
            return $this->error('Task not found.', null, 404);

        } catch (ValidationException $e) {
            return $this->error('Validation failed.', $e->errors(), 422);

        } catch (Exception $e) {
            Log::error('Task Update Error: ' . $e->getMessage());
            return $this->error('Something went wrong while updating task.', null, 500);
        }
    }

    /**
     * DELETE TASK
     */
    public function destroy($id)
    {
        try {
            $task = Task::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

            $task->delete();

            Cache::tags(['tasks_user_' . Auth::id()])->flush();

            return $this->success('Task deleted successfully.');

        } catch (ModelNotFoundException $e) {
            return $this->error('Task not found.', null, 404);

        } catch (Exception $e) {
            Log::error('Task Delete Error: ' . $e->getMessage());
            return $this->error('Something went wrong while deleting task.', null, 500);
        }
    }
}
