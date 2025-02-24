<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id'); //kategori_menu_id
            $table->foreignId('user_id');
            $table->string('title'); //nama_menu
            $table->string('slug')->unique();
            $table->text('excerpt'); //field untuk menyimpan sebagian kecil dari tulisan body blog kita 'read more'
            $table->text('body'); //deskripsi menu
            $table->string('image')->nullable(); // gambar
            $table->timestamp('published_at')->nullable(); // tipe data timestamp
            $table->timestamps(); // untuk creat at/updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
