<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'user']);

        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->only(['title', 'description', 'due_date', 'priority', 'status', 'project_id', 'user_id']);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:basse,moyenne,haute',
            'status' => 'nullable|in:en_cours,termine,annule',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create($validated);

        // Auto-assign project to user if the project is currently unassigned
        if ($validated['project_id'] && $validated['user_id']) {
            $project = \App\Models\Project::find($validated['project_id']);
            if ($project && is_null($project->user_id)) {
                $project->update(['user_id' => $validated['user_id']]);
            }
        }

        return $task;
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task->load(['project', 'user']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->only(['title', 'description', 'due_date', 'priority', 'status', 'project_id']);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:basse,moyenne,haute',
            'status' => 'nullable|in:en_cours,termine,annule',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $task->update($data);
        return $task->fresh(['project', 'user']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
