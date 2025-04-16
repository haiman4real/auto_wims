<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\AuthController;
use App\Http\Controllers\ApiController\PaymentController;



// Public routes
Route::prefix('v1')->middleware(['cors', 'forceJsonResponse', 'throttle:60,1'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

});

// Protected routes
Route::prefix('v1')->middleware(['cors', 'forceJsonResponse', 'throttle:60,1', 'check_api_token'])->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('invoice', PaymentController::class);
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    });
});