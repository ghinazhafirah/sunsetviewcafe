<?php

use App\Http\Controllers\Api\Payment\ApiPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/payments/callback', [PaymentController::class, 'callback']);

Route::controller(ApiPaymentController::class)->group(function () {
    Route::post('/payments/process', 'processPayment')->name('api.payment.process');
});
