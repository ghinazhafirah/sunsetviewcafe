<?php

use Illuminate\Support\Facades\Route; //koneksi otomatis ke model
use App\Http\Controllers\PaymentController;

Route::post('/payments/callback', [PaymentController::class, 'callback']); // Midtrans callback

