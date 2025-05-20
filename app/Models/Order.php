<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_id',      
        'customer_name', 
        'customer_whatsapp', 
        'kode_transaction',
        'total_price',
        'payment_method',
        'table_number',
        'status'];
        // agar id transaksi lebih aman dan tidak gampang terdeteksi
    protected static function boot()
    {
        parent::boot();

        // Generate UUID otomatis setiap kali transaksi dibuat
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            
        });
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'order_id', 'order_id');
        // orders.order_id → carts.order_id
    }
    
    // public function post()
    // {
    //     return $this->belongsTo(Post::class, 'posts_id'); // atau 'post_id' tergantung kolomnya
    // }

    // Gunakan UUID sebagai kunci utama saat melakukan query
    public function getRouteKeyName()
    {
        return 'uuid';
    }

     public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }


}