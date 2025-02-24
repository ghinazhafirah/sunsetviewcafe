<?php

namespace App\Models;

class Post
{
    private static $menu_posts = [      //porpertis statis sementara akses kelas
        [
            "title" => "Makanan",  //ini ambil data dari database
            "slug" => "makanan",
            "body" => "Makanan utama di Sunset View Cafe cenderung mengusung konsep fusion food, menggabungkan cita rasa lokal dan internasional. Beberapa kategori makanan utama yang biasanya ada yaitu Western Food, Asian Food dan Indonesian Food."
        ],
        [
            "title" => "Snack",
            "slug" => "snack",
            "body" => "Camilan di Sunset View Cafe lebih ke arah light bites yang cocok dinikmati saat bersantai atau menemani minuman."
        ],
        [
            "title" => "Minuman",
            "slug" => "minuman",
            "body" => "Minuman yang tersedia beragam, mulai dari kopi hingga minuman segar untuk menemani momen sunset."         
        ]         
    ];

    // public static function all()
    // {
    //     return collect(self::$menu_posts); //self itu untuk statis, tapi static untuk metode static
    // }

    public static function Limited() {
        return collect(self::limit(100))->get();
    }
    

    public static function find($slug) 
    {
        $posts = static::all(); //mengambil semua postnya
        return $posts->firstWhere('slug', $slug); //ambil semua post bentuk colecttion yg pertama kali ditemukan slugnya = slug
    }

}
