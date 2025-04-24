<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  
    use HasFactory;

    protected $table = 'carts';
    
    protected $fillable = [
        'token',
        'table_number', 
        'order_id', 
        'posts_id', 
        'quantity', 
        'total_menu',
        'note'
    ];

    // Relasi ke Post (menu)
    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id'); // atau 'post_id' tergantung kolomnya
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

}
