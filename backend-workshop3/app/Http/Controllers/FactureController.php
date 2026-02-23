<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Facture::with(['client', 'project'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number_facture' => 'required|string|unique:factures,number_facture',
            'amount' => 'required|integer',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'nullable|in:draft,paid,sent,cancelled',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        return Facture::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture)
    {
        return $facture->load(['client', 'project', 'payments']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'number_facture' => 'sometimes|required|string|unique:factures,number_facture,' . $facture->id,
            'amount' => 'sometimes|required|integer',
            'issue_date' => 'sometimes|required|date',
            'due_date' => 'sometimes|required|date',
            'status' => 'nullable|in:draft,paid,sent,cancelled',
            'client_id' => 'sometimes|required|exists:clients,id',
            'project_id' => 'sometimes|required|exists:projects,id',
        ]);

        $facture->update($validated);
        return $facture;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facture $facture)
    {
        $facture->delete();
        return response()->noContent();
    }
}
