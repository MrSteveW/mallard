<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => Task::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function store()
    {
        Task::create([
            'name' => request('name'),
        ]);

        return redirect('/tasks');
    }

    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', ['task' => $task]);
    }

    public function update(Task $task)
    {
        $task->update([
            'name' => request('name'),
        ]);

        return redirect('tasks/');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/tasks');
    }
}
