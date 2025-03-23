<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = ['id']; //properti yang gaboleh diisi
    protected $with = ['category'];
    protected $casts = ['images' => 'array'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
