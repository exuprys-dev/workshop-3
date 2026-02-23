<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Client::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|in:actif,inatif,prospect',
        ]);

        return Client::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return $client->load(['projects', 'factures']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|in:actif,inatif,prospect',
        ]);

        $client->update($validated);
        return $client;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }
}
