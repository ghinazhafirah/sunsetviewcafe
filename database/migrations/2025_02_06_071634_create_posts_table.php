<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id'); //fk untuk tabel kategori
            $table->foreignId('user_id'); //fk untuk tabel kategori
            $table->string('title');
            $table->integer('price')->default(0);
            $table->string('slug')->unique();
            $table->enum('status', ['available', 'not_available'])->default('available');
            $table->string('image')->nullable();
            $table->text('excerpt'); //field untuk menyimpan sebagian kecil dari tulisan body blog kita 'read more'
            $table->text('body');
            $table->timestamps(); // untuk creat at/updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
