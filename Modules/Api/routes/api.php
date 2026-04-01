<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\v1\Movie\MovieController;
use Modules\Api\Http\Controllers\V1\Payment\PaymentController;
use Modules\Api\Http\Controllers\V1\Auth\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::middleware('role:admin')->group(function () {
            Route::post('/purchases', [\Modules\Api\Http\Controllers\V1\Purchase\PurchaseController::class, 'purchase']);
            Route::post('/purchases/refund', [\Modules\Api\Http\Controllers\V1\Purchase\PurchaseController::class, 'refundProvider']);
            Route::get('/products/available', [\Modules\Api\Http\Controllers\V1\Product\ProductController::class, 'availableProducts']);
            Route::post('/orders', [\Modules\Api\Http\Controllers\V1\Order\OrderController::class, 'createOrder']);
            Route::get('/reports/remaining-quantities', [\Modules\Api\Http\Controllers\V1\Report\ReportController::class, 'remainingQuantities']);
            Route::get('/reports/batch-profit', [\Modules\Api\Http\Controllers\V1\Report\ReportController::class, 'batchProfit']);
        });
    });
});
