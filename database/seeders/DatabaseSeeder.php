<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        user::create([
            'name' => 'Ghina Zhafirah',
            'email' => 'Ghinazhafirah@gmail.com',
            'password' => bcrypt('12345')
        ]);

        // user::create([
        //     'name' => 'Agustin Ghina',
        //     'email' => 'Ghinaagustin@gmail.com',
        //     'password' => bcrypt('67890')
        // ]);

        // User::factory(3)->create();

        Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan'
        ]);

        Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman'
        ]);

        Category::create([
            'name' => 'Snack',
            'slug' => 'snack'
        ]);
        
        // Post::factory(20)->create();
        Post::create([
            'title' => 'Penyetan',
            'slug' => 'penyetan',
            'excerpt' => 'Hidangan khas Indonesia yang terdiri dari ayam goreng yang dipenyet (ditekan) dengan ulekan dan disajikan dengan sambal khas',
            'body' => 'ayam goreng dengan sambel yang pedes bangeeeetttttt',
            // 'images' => json_encode(["posts/ayampenyet.jpg"]),
            'harga' => '20.000',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Ramesan',
            'slug' => 'ramesan',
            'excerpt' => 'Menu khas warung makan dengan lauk beragam yang disajikan dalam satu piring',
            'body' => 'nasi dengan sayur dan lauk ayam ditambah kerupuk',
            'images' => json_encode(["posts/ayampenyet.jpg", "posts/magelangan.jpg"]),
            'harga' => '18.000',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Magelangan',
            'slug' => 'magelangan',
            'excerpt' => 'Menu nasi goreng khas Jawa yang dicampur dengan mi goreng dan bumbu rempah khas',
            'body' => 'Nasi dan mi goreng dengan suwiran ayam, telur, dan acar timun',
            // 'images' => json_encode(["posts/magelangan.jpg"]),
            'harga' => '15.000',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Es Teh',
            'slug' => 'es teh',
            'excerpt' => 'Minuman segar berbahan dasar teh yang cocok untuk menemani hidangan',
            'body' => 'Teh dingin dengan gula pasir atau gula cair',
            'harga' => '3.000',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Seblak',
            'slug' => 'seblak',
            'excerpt' => 'Hidangan khas Sunda yang terbuat dari kerupuk basah dengan bumbu pedas gurih',
            'body' => 'Campuran lengkap kerupuk, telur, ceker, bakso, dan sosis',
            'harga' => '15.000',
            'category_id' => 3,
            'user_id' => 2
        ]);

        // Cart::create([
        //     'jumlah_menu' => 'Seblak',
        //     'slug' => 'seblak',
        //     'excerpt' => 'Hidangan khas Sunda yang terbuat dari kerupuk basah dengan bumbu pedas gurih',
        //     'body' => 'Campuran lengkap kerupuk, telur, ceker, bakso, dan sosis',
        //     'harga' => '15.000',
        //     'category_id' => 3,
        //     'user_id' => 2
        // ]);
    }
}
