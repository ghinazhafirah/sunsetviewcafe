<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Sesuaikan dengan field di tabel categories
    
    public function posts() //menghubungkan category dengan post
    {
        return $this->hasMany(Post::class);
    }
}
