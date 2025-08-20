<?php

use App\Http\Controllers\Api\SupportRequestController;
use Illuminate\Support\Facades\Route;

// Public endpoint to submit support requests
Route::post('/requests', [SupportRequestController::class, 'store']);

// Admin endpoints protected by token middleware
Route::middleware('admin.token')->group(function () {
    Route::get('/requests', [SupportRequestController::class, 'index']);
    Route::put('/requests/{id}', [SupportRequestController::class, 'update']);
});


