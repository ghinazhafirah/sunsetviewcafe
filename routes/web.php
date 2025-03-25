<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Livewire;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\QrCodeController;

use Illuminate\Support\Facades\Route; //koneksi otomatis ke model
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

Route::get('/cart', function () {
    return view('cart', [
        "title" => "checkout",
    ]) ;   
});

Route::get('/dashboard', function () {
    return view('dashboard', [
         "title" => "dashboard",
         "image" => "logocafe.png",
    ]) ;   
});

Route::get('/qr', function () {
    return view('qr.index');
});


//menu
Route::get('/menu/{table?}', [PostController::class, 'index'])->name('menu');
Route::get('/menu/{table?}/{slug}', [PostController::class, 'show'])->name('post.show');

//menu juga tapi untuk file posts
Route::get('/posts/{slug}/{tableNumber?}', [PostController::class, 'show'])
    ->where('tableNumber', '[0-9]+')
    ->name('posts.show');

//cart
Route::get('/cart/{table?}', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::match(['get', 'post'], '/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

//cart
Livewire::listen('addToCart', [CartController::class, 'addToCart']);  

//QR
Route::get('/qr', [QrCodeController::class, 'showQrForm'])->name('qr.form');
Route::post('/generate-qr', [QrCodeController::class, 'generateQrCode'])->name('generate.qr');
Route::get('/meja/{table}', [QrCodeController::class, 'redirectToMenu'])->name('menu.redirect');

//session
Route::get('/test-session', [QrCodeController::class, 'testSession']);
Route::get('/cek-session', function () {
    return session('tableNumber') ?? 'Session kosong';});

//BELUM JADI YAAA//punya brina
Route::get('/checkout/{table?}', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/store', [CheckoutController::class, 'storeCustomerData'])->name('checkout.storeCustomerData');
Route::get('/checkout/success/{uuid}', [CheckoutController::class, 'success'])->name('checkout.success');