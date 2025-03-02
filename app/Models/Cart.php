<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  
    use HasFactory;
    protected $fillable = ['pesenan_id', 'posts_id', 'jumlah_menu', 'total_menu'];

    // Relasi ke Post (menu)
    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }
    
}
