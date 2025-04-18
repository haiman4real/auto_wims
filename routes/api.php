<?php

use App\Http\Controllers\AllowedIpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\AuthController;
use App\Http\Controllers\ApiController\PaymentController;




// Public routes
Route::prefix('v1')->middleware(['cors', 'forceJsonResponse', 'throttle:60,1'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');


    Route::post('/onboard-ip', [AllowedIpController::class, 'onboardIp'])->name('onboardIpStore');
    Route::get('/ip/{id}/approve', [AllowedIpController::class, 'approveIp'])->name('ip.approve');
    Route::get('/ip/{id}/decline', [AllowedIpController::class, 'declineIp'])->name('ip.decline');
    Route::get('/ip/{id}/more-info', [AllowedIpController::class, 'requestMoreInfo'])->name('ip.moreInfo');
    Route::get('/ip/{id}/request-more-info', [AllowedIpController::class, 'loadRequestInfoForm'])->name('ip.requestMoreInfoForm');
    Route::post('/ip/{id}/request-more-info', [AllowedIpController::class, 'submitRequestMoreInfo'])->name('ip.submitRequestMoreInfo');
    Route::get('/ip/{id}/submit-info', [AllowedIpController::class, 'userViewRequestedInfo'])->name('ip.userViewRequestedInfo');
    Route::post('/ip/{id}/submit-info', [AllowedIpController::class, 'userSubmitRequestedInfo'])->name('ip.userSubmitRequestedInfo');


});

// Protected routes
Route::prefix('v1')->middleware(['cors', 'forceJsonResponse', 'throttle:60,1', 'check_api_token'])->group(function () {

    Route::middleware('auth:api')->group(function () {
        // Route::apiResource('/payment/invoice', PaymentController::class);
        // Route::post('/payment/response', [PaymentController::class, 'handleResponse'])->name('api.payments.response');
        // Route::post('/token/revoke', [AuthController::class, 'logout'])->name('api.logout');

         Route::get('/allowed-ips', [AllowedIpController::class, 'index'])->name('allowIPApi');
        Route::post('/allowed-ips', [AllowedIpController::class, 'store'])->name('allowIPApiStore');
        Route::put('/allowed-ips/{id}', [AllowedIpController::class, 'update'])->name('allowIPApiUpdate');
        Route::delete('/allowed-ips/{id}', [AllowedIpController::class, 'destroy'])->name('allowIPApiDestroy');

    });
});

Route::prefix('v2')->middleware(['cors', 'forceJsonResponse', 'restrict.ip', 'check_api_token', 'throttle:60,1'])->group(function () {
    Route::post('/token/authorize', [AuthController::class, 'login'])->name('api.login');
    Route::post('/token/refresh', [AuthController::class, 'refreshToken'])->name('api.refreshToken');

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('/payment/invoice', PaymentController::class);
        Route::post('/payment/response', [PaymentController::class, 'handleResponse'])->name('api.payments.response');
        Route::post('/token/revoke', [AuthController::class, 'logout'])->name('api.logout');
    });
});