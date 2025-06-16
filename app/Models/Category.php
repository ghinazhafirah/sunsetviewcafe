<?php

namespace App\Models;

// use Illuminate\Database\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    // use HasFactory;
    protected $guarded = ['id']; //properti yang gaboleh diisi

    public function posts() //untuk menghubungkan kategori dengan post
    {
        return $this->hasMany(Post::class); //1:N post
    }
}
