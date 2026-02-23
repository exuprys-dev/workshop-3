<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Depense::with(['user', 'project'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'amount' => 'required|integer',
            'date' => 'required|date',
            'justification' => 'nullable|string',
            'categorie' => 'nullable|in:materiel,logiciel,"infrastuctures et services cloud",autre',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        return Depense::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Depense $depense)
    {
        return $depense->load(['user', 'project']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Depense $depense)
    {
        $data = $request->only(['libelle', 'amount', 'date', 'categorie', 'project_id']);

        $request->validate([
            'libelle' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'date' => 'sometimes|required|date',
            'categorie' => 'nullable|string|max:100',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $depense->update($data);
        return $depense->fresh(['project']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense)
    {
        $depense->delete();
        return response()->noContent();
    }
}
