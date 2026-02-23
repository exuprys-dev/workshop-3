<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Payment::with('facture')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer',
            'date' => 'required|date',
            'method' => 'nullable|in:virement,espece,cheque',
            'reference' => 'required|string',
            'status' => 'nullable|in:draft,paid,sent,cancelled',
            'facture_id' => 'required|exists:factures,id',
        ]);

        $payment = Payment::create($validated);

        // Auto-update Facture status if fully paid
        $facture = \App\Models\Facture::with('payments')->find($validated['facture_id']);
        if ($facture) {
            $totalPayments = $facture->payments->sum('amount');
            if ($totalPayments >= $facture->amount) {
                $facture->update(['status' => 'paid']);
            }
        }

        return $payment;
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $payment->load('facture');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|required|integer',
            'date' => 'sometimes|required|date',
            'method' => 'nullable|in:virement,espece,cheque',
            'reference' => 'sometimes|required|string',
            'status' => 'nullable|in:draft,paid,sent,cancelled',
            'facture_id' => 'sometimes|required|exists:factures,id',
        ]);

        $payment->update($validated);
        return $payment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
