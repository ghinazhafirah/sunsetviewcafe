<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345')
        ]);

        Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan'
        ]);

        Category::create([
            'name' => 'Snack',
            'slug' => 'snack'
        ]);

        Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman'
        ]);

        Post::create([
            'title' => 'Nasi Bakar Ayam Jamur',
            'slug' => 'nasi-bakar-ayam-jamur',
            'price' => '17000',
            'excerpt' => 'Nasi berbumbu khas dibungkus daun pisang, berisi ayam suwir dan jamur.',
            'body' => 'Nasi berbumbu khas dibungkus daun pisang, berisi ayam suwir dan jamur.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Nasi Bakar Ikan Pindang',
            'slug' => 'nasi-bakar-ikan-pindang',
            'price' => '17000',
            'excerpt' => 'Nasi berbumbu khas dibungkus daun pisang, berisi ikan pindang yang gurih.',
            'body' => 'Nasi berbumbu khas dibungkus daun pisang, berisi ikan pindang yang gurih.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Penyetan Ayam Paha',
            'slug' => 'penyetan-ayam-paha',
            'price' => '20000',
            'excerpt' => 'Nasi dengan ayam goreng, lalapan segar, dan sambal penyet khas.',
            'body' => 'Nasi dengan ayam goreng, lalapan segar, dan sambal penyet khas.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Penyetan Ayam Dada',
            'slug' => 'penyetan-ayam-dada',
            'price' => '20000',
            'excerpt' => 'Nasi dengan ayam goreng, lalapan segar, dan sambal penyet khas.',
            'body' => 'Nasi dengan ayam goreng, lalapan segar, dan sambal penyet khas.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Penyetan Ayam Pahe',
            'slug' => 'penyetan-ayam-pahe',
            'price' => '16000',
            'excerpt' => 'Paket versi lebih hemat dengan potongan ayam, lalapan, dan sambal penyet.',
            'body' => 'Paket versi lebih hemat dengan potongan ayam, lalapan, dan sambal penyet.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Penyetan Rempelo Ati',
            'slug' => 'penyetan-rempelo-ati',
            'price' => '14000',
            'excerpt' => 'Rempelo ati goreng dengan nasi, lalapan, dan sambal penyet pedas.',
            'body' => 'Rempelo ati goreng dengan nasi, lalapan, dan sambal penyet pedas.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Penyetan Telur Dadar',
            'slug' => 'penyetan-telur-dadar',
            'price' => '14000',
            'excerpt' => 'Telur dadar gurih disajikan dengan nasi, lalapan, dan sambal penyet.',
            'body' => 'Telur dadar gurih disajikan dengan nasi, lalapan, dan sambal penyet.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Bakmi Nyemek',
            'slug' => 'bakmi-nyemek',
            'price' => '18000',
            'excerpt' => 'Mi rebus kuah kental dengan bumbu gurih dan topping telur sayuran.',
            'body' => 'Mi rebus kuah kental dengan bumbu gurih dan topping telur sayuran.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Bakmi Ghodog',
            'slug' => 'bakmi-ghodog',
            'price' => '18000',
            'excerpt' => 'Mi rebus khas Jawa dengan kuah hangat dan bumbu rempah.',
            'body' => 'Mi rebus khas Jawa dengan kuah hangat dan bumbu rempah.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Bakmi Goreng',
            'slug' => 'bakmi-goreng',
            'price' => '18000',
            'excerpt' => 'Mi goreng berbumbu khas dengan topping sayuran.',
            'body' => 'Mi goreng berbumbu khas dengan topping sayuran.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Nasi Goreng Original',
            'slug' => 'nasi-goreng-original',
            'price' => '15000',
            'excerpt' => 'Nasi goreng dengan telur dan bumbu khas.',
            'body' => 'Nasi goreng dengan telur dan bumbu khas.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Nasi Goreng Ruwet',
            'slug' => 'nasi-goreng-ruwet',
            'price' => '21000',
            'excerpt' => 'Kombinasi nasi dan mi goreng dengan bumbu khas dan topping komplet.',
            'body' => 'Kombinasi nasi dan mi goreng dengan bumbu khas dan topping komplet.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Indomie Biasa',
            'slug' => 'indomie-biasa',
            'price' => '9000',
            'excerpt' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'body' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Indomie Jumbo',
            'slug' => 'indomie-jumbo',
            'price' => '11000',
            'excerpt' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'body' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Indomie Premium',
            'slug' => 'indomie-premium',
            'price' => '14000',
            'excerpt' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'body' => 'Indomie dengan berbagai ukuran, bisa ditambah topping sesuai selera.',
            'category_id' => 1,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Churos',
            'slug' => 'churos',
            'price' => '14000',
            'excerpt' => 'Gorengan renyah berbentuk panjang dengan taburan gula dan coklat.',
            'body' => 'Gorengan renyah berbentuk panjang dengan taburan gula dan coklat.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'French Fries',
            'slug' => 'french-fries',
            'price' => '14000',
            'excerpt' => 'Kentang goreng renyah dengan saus pendamping.',
            'body' => 'Kentang goreng renyah dengan saus pendamping.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Lunpia',
            'slug' => 'lunpia',
            'price' => '18000',
            'excerpt' => 'Lunpia goreng isi rebung khas Semarang.',
            'body' => 'Lunpia goreng isi rebung khas Semarang.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Pempek',
            'slug' => 'pempek',
            'price' => '20000',
            'excerpt' => 'Pempek Palembang dengan kuah cuko khas.',
            'body' => 'Pempek Palembang dengan kuah cuko khas.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Mix Platter',
            'slug' => 'mix-platter',
            'price' => '20000',
            'excerpt' => 'Paduan camilan goreng seperti kentang, nugget, dan sosis.',
            'body' => 'Paduan camilan goreng seperti kentang, nugget, dan sosis.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Roti Maryam',
            'slug' => 'roti-maryam',
            'price' => '12000',
            'excerpt' => 'Roti pipih dengan topping coklat, keju, atau kombinasi.',
            'body' => 'Roti pipih dengan topping coklat, keju, atau kombinasi.',
            'category_id' => 2,
            'user_id' => 1
        ]);
        
        Post::create([
            'title' => 'Cireng',
            'slug' => 'cireng',
            'price' => '9000',
            'excerpt' => 'Cireng gurih isi 5 dengan isian bumbu khas.',
            'body' => 'Cireng gurih isi 5 dengan isian bumbu khas.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Sempolan',
            'slug' => 'sempolan',
            'price' => '9000',
            'excerpt' => 'Sempolan ayam renyah isi 5 dengan saus pilihan.',
            'body' => 'Sempolan ayam renyah isi 5 dengan saus pilihan.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Tahu Aci',
            'slug' => 'tahu-aci',
            'price' => '10000',
            'excerpt' => 'Tahu goreng isi 5 dengan adonan aci kenyal di dalamnya.',
            'body' => 'Tahu goreng isi 5 dengan adonan aci kenyal di dalamnya.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Tahu Bakso',
            'slug' => 'tahu-bakso',
            'price' => '10000',
            'excerpt' => 'Tahu goreng isi 5 bakso',
            'body' => 'Tahu goreng isi 5 bakso',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Bakso Sayur',
            'slug' => 'bakso-sayur',
            'price' => '9000',
            'excerpt' => 'Bakso isi 5 dengan campuran sayur sehat.',
            'body' => 'Bakso isi 5 dengan campuran sayur sehat.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Pangsit',
            'slug' => 'pangsit',
            'price' => '14000',
            'excerpt' => 'Pangsit dengan pilihan goreng atau kukus.',
            'body' => 'Pangsit dengan pilihan goreng atau kukus.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Dimsum',
            'slug' => 'dimsum',
            'price' => '17000',
            'excerpt' => 'Dimsum ayam lezat atau campuran berbagai varian.',
            'body' => 'Dimsum ayam lezat atau campuran berbagai varian.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Kuah Seblak Cireng',
            'slug' => 'kuah-seblak-cireng',
            'price' => '17000',
            'excerpt' => 'Kuah Seblak pedas dengan pilihan isian.',
            'body' => 'Kuah Seblak pedas dengan pilihan isian.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Kuah Seblak Mi Nyemek',
            'slug' => 'kuah-seblak-mi-nyemek',
            'price' => '18000',
            'excerpt' => 'Kuah Seblak pedas dengan pilihan isian.',
            'body' => 'Kuah Seblak pedas dengan pilihan isian.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Kuah Seblak Pangsit',
            'slug' => 'kuah-seblak-pangsit',
            'price' => '19000',
            'excerpt' => 'Kuah Seblak pedas dengan pilihan isian.',
            'body' => 'Kuah Seblak pedas dengan pilihan isian.',
            'category_id' => 2,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Milk Based',
            'slug' => 'milk-based',
            'price' => '19000',
            'excerpt' => 'Campuran Susu dengan varian rasa.',
            'body' => 'Campuran Susu dengan varian rasa.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Variant Tea',
            'slug' => 'variant-tea',
            'price' => '9000',
            'excerpt' => 'Teh dengan berbagai rasa sesuai pilihan.',
            'body' => 'Teh dengan berbagai rasa sesuai pilihan.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Nutrisari',
            'slug' => 'nutrisari',
            'price' => '6000',
            'excerpt' => 'Minuman serbuk dengan rasa buah segar.',
            'body' => 'Minuman serbuk dengan rasa buah segar.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Good Day',
            'slug' => 'good-day',
            'price' => '7000',
            'excerpt' => 'Kopi susu instan dengan berbagai varian rasa.',
            'body' => 'Kopi susu instan dengan berbagai varian rasa.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Drink Beng-Beng',
            'slug' => 'drink-beng-beng',
            'price' => '7000',
            'excerpt' => 'Minuman coklat dengan rasa khas Beng-Beng.',
            'body' => 'Minuman coklat dengan rasa khas Beng-Beng.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Susu Zee',
            'slug' => 'susu-zee',
            'price' => '8000',
            'excerpt' => 'Susu pertumbuhan dengan rasa vanilla dan coklat.',
            'body' => 'Susu pertumbuhan dengan rasa vanilla dan coklat.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Air Putih',
            'slug' => 'air-putih',
            'price' => '2000',
            'excerpt' => 'Air mineral segar untuk melepas dahaga.',
            'body' => 'Air mineral segar untuk melepas dahaga.',
            'category_id' => 3,
            'user_id' => 1
        ]);

        Post::create([
            'title' => 'Air Mineral Botol',
            'slug' => 'air-mineral-botol',
            'price' => '5000',
            'excerpt' => 'Air mineral segar untuk melepas dahaga.',
            'body' => 'Air mineral segar untuk melepas dahaga.',
            'category_id' => 3,
            'user_id' => 1
        ]);
        

       
        
        
        

    }
}
