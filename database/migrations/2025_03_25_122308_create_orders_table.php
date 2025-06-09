<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID untuk transaksi (pengganti ID numerik di URL)
            $table->string('order_id')->nullable(); 
            $table->string('table_number')->default(0); // Nomor meja dari QR Code
            $table->string('customer_name'); // Nama pelanggan
            $table->string('customer_whatsapp'); // Nomor WhatsApp pelanggan
            $table->string('customer_email'); // Email pelanggan
            $table->string('kode_transaction')->nullable()->unique(); // COBA DULU NULL ID transaksi unik dari sistem / Midtrans/TRX-..
            $table->integer('total_price')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending'); // Tambah status 'cancelled'
            $table->string('payment_method'); // Default cash
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
