<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DepenseController;

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Public for all authenticated users
    Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
    Route::apiResource('tasks', TaskController::class);

    // Admin only routes
    Route::middleware('can:admin-only')->group(function () {
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
        Route::apiResource('factures', FactureController::class);
        Route::apiResource('payments', PaymentController::class);
        Route::apiResource('depenses', DepenseController::class);
        Route::apiResource('users', \App\Http\Controllers\UserController::class);

        // Finance aliases
        Route::get('/finance/invoices', [FactureController::class, 'index']);
        Route::get('/finance/payments', [PaymentController::class, 'index']);
        Route::get('/finance/expenses', [DepenseController::class, 'index']);
        Route::put('/finance/invoices/{facture}', [FactureController::class, 'update']);
    });
});
