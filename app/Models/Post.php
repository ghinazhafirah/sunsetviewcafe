<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;
  
    protected $guarded = ['id']; //properti yang gaboleh diisi
    protected $with = ['category'];

    public function category() //nama metod itu sama dengan nama modelnya
    {
        return $this->belongsTo(Category::class); //model post terhadap model kategory, agar 1 postingan punya 1 kategori
    }

    public function user()
    {
        return $this->belongsTo(User::class); //1 post hanya bisa dibuat 1 user

    }

    public function getRouteKeyName() //setiap route otomatis mencari slug
    {
        return 'slug';
    }


    public function sluggable(): array //agar ketika mengisikan title lalu tab-slug bisa terisi otomatis
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
