<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // protected $fillable = ['title', 'excerpt', 'body']; //penting, properti yang boleh diisi
    protected $guarded = ['id']; //properti yang gaboleh diisi
    protected $with = ['category'];
    protected $casts = ['images' => 'array'];
    

    public function category()
    {
        // return $this->belongsTo(Category::class, 'category_id'); //modelpost terhadap model category, agar 1 post punya 1 category
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
