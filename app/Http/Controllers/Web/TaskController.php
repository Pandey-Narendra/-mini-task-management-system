<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;

use App\Models\Task;

class TaskController extends Controller
{
    /**
     * LIST TASKS (Web)
     */
    public function index()
    {
        // $tomorrow = Carbon::tomorrow()->toDateString();

        // $tasks = Task::with('user')
        //     ->whereDate('due_date', $tomorrow)
        //     ->get()
        // ;

        // dd($tasks);
        try {
            $tasks = Task::select('id','title','status','due_date')
                ->where('user_id', Auth::id())
                ->orderBy('id', 'desc')
                ->paginate(2);

            return view('tasks.index', compact('tasks'));

        } catch (Exception $e) {
            Log::error("WEB Task List Error: ".$e->getMessage());
            return redirect()->back()->with('error', 'Unable to load your tasks right now.');
        }
    }

    /**
     * SHOW CREATE FORM
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * STORE NEW TASK (Web)
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

            Task::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tasks.index')
                ->with('success', 'Task created successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {
            Log::error("WEB Task Store Error: ".$e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while creating the task.');
        }
    }

    /**
     * SHOW EDIT FORM
     */
    public function edit($id)
    {
        try {
            $task = Task::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

            return view('tasks.edit', compact('task'));

        } catch (ModelNotFoundException $e) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found or not accessible.');

        } catch (Exception $e) {
            Log::error("WEB Task Edit Error: ".$e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the edit form.');
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

            return redirect()->route('tasks.index')
                ->with('success', 'Task updated successfully.');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found or access denied.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {
            Log::error("WEB Task Update Error: ".$e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while updating the task.');
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

            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted successfully.');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found or access denied.');

        } catch (Exception $e) {
            Log::error("WEB Task Delete Error: ".$e->getMessage());
            return redirect()->back()->with('error', 'Unable to delete the task.');
        }
    }
}
