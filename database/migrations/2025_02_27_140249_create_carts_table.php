<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('token')->nullable();
            $table->integer('table_number')->default(0);
            $table->string('order_id'); 
            $table->foreignId('posts_id');
            $table->integer('quantity');
            $table->string('total_menu');
            $table->timestamps();
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
