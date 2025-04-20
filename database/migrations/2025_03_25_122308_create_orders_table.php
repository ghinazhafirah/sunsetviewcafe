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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Relasi ke tabel users (nullable untuk tamu)
            $table->string('table_number')->default(0); // Nomor meja dari QR Code
            $table->string('customer_name'); // Nama pelanggan
            $table->string('customer_whatsapp'); // Nomor WhatsApp pelanggan
            $table->string('kode_transaction')->nullable()->unique(); // COBA DULU NULL ID transaksi unik dari sistem / Midtrans/TRX-..
            $table->decimal('total_price', 10, 2); // Total harga
        
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
        Schema::dropIfExists('orders');
    }
};
