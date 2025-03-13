<?php

use App\Models\Post;
use App\Models\Category;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route; //koneksi otomatis ke model

Route::get('/', function () {       //halaman utama ketika url diakses
    return view('dashboard', [          //ubah bagian view
         "title" => "dashboard",
         "image" => "logocafe.png"
    ]) ;   
});

Route::get('/home', function (){
    return view('home', [
        "title" => "home",
        "name" => "Sunset View Cafe",
        "email" => "sunsetviewcandisari@gmail.com",
        "image" => "logocafe.png",
        'active' => 'home',
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard', [
         "title" => "dashboard",
         "image" => "logocafe.png",
    ]) ;   
});

// Route::get('/menu', [PostController::class, 'index']);
Route::get('/menu/{tableNumber?}', [PostController::class, 'index'])->name('menu');

// Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::get('/cart/{tableNumber?}', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::match(['get', 'post'], '/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::get('/posts/{slug}/{tableNumber?}', [PostController::class, 'show'])->name('posts.show');
// Route::get('/post/{slug}/{tableNumber?}', [PostController::class, 'show'])->name('post.show');

Route::get('/posts/{slug}/{tableNumber?}', [PostController::class, 'show'])
    ->where('tableNumber', '[0-9]+')
    ->name('posts.show');

Route::get('/generate-all-qrs', [QrCodeController::class, 'showQrCode']);

Route::get('/test-session', [QrCodeController::class, 'testSession']);

Route::get('/cek-session', function () {
    return session('tableNumber') ?? 'Session kosong';
});
