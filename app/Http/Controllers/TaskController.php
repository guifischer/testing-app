<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{

    /**
     * Display the list of tasks
     */
    public function show(Request $request, TaskService $taskService): Response
    {
        $tasks = $taskService->getTasks($request);
        return Inertia::render('Dashboard/Index', $tasks);
    }

    /**
     * create the task.
     */
    public function create(TaskUpdateRequest $request, TaskService $taskService): RedirectResponse
    {
        $taskService->create($request->validated(), $request->user()->id);
        return Redirect::route('dashboard');
    }

    /**
     * Update the task status to `in progress` and attribute it to the user.
     */
    public function takeOn(Request $request, TaskService $taskService, Task $task): RedirectResponse
    {
        $taskService->takeOnTask($task, $request->user()->id);
        return Redirect::route('dashboard');
    }

    /**
     * Update the task information.
     */
    public function update(TaskUpdateRequest $request, TaskService $taskService, Task $task): RedirectResponse
    {
        $taskService->update($task, $request->validated());
        return Redirect::route('dashboard');
    }

    /**
     * Delete the task
     */
    public function destroy(Request $request, TaskService $taskService, Task $task): RedirectResponse
    {
        $taskService->delete($task);
        return Redirect::route('dashboard');
    }
}
