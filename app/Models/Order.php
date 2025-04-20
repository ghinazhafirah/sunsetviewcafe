<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
      
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

    // Gunakan UUID sebagai kunci utama saat melakukan query
    public function getRouteKeyName()
    {
        return 'uuid';
    }

}