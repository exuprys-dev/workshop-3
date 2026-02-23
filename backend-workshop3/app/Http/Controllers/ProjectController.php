<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['user', 'client']);

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
        $validated = $request->only(['name', 'description', 'start_date', 'end_date', 'budget', 'status', 'client_id', 'user_id']);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'budget' => 'required|numeric',
            'status' => 'nullable|in:en_cours,termine,annule',
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'nullable|exists:users,id',
        ];

        $request->validate($rules);

        return Project::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return $project->load(['client', 'user', 'tasks', 'depenses']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->only(['name', 'description', 'start_date', 'end_date', 'budget', 'status', 'client_id']);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'budget' => 'sometimes|required|numeric',
            'status' => 'nullable|in:en_cours,termine,annule',
            'client_id' => 'sometimes|required|exists:clients,id',
        ]);

        $project->update($data);
        return $project->fresh(['client']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
