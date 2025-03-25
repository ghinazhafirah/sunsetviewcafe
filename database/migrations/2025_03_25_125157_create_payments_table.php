<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID untuk transaksi (pengganti ID numerik di URL)
            $table->foreignId('order_id');        
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending'); // Tambah status 'cancelled'
            $table->string('payment_method')->default('cash'); // Default cash
            // $table->string('payment_method')->nullable(); // Metode pembayaran (ex: 'gopay', 'bank_transfer')
            $table->json('payment_response')->nullable(); // Data respons dari Midtrans
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran sukses
    
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
