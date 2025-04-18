<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Livewire;

use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPostController;
use Illuminate\Support\Facades\Route; //koneksi otomatis ke model
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', function () {       //halaman utama ketika url diakses
    return view('dashboard.index', [          //ubah bagian view
         "title" => "dashboard",
         "name" => "Sunset View Cafe",
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

Route::get('/categories', function() {
    return view('categories',[
        'title' => 'Post Categories',
        'active' => 'categories',
        'categories' => Category::all() //mengambil model dari kategori kita
    ]);
});


Route::get('/categories/{category:slug}', function(Category $category){
    return view('category',[
        'title' => $category->name,
        'active' => 'categories',
        'posts' => $category->posts, //1 kategori punya banyak post (dari metod post ke model category)
        'category' => $category->name
    ]);
});

Route::get('/qr', function () {
    return view('qr.index', [
        "image" => "logocafe.png",
    ]);
});


//login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

//register
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

//dashboard
Route::get('dashboard/posts/checkSlug',[DashboardPostController::class, 'checkSlug'])->middleware('auth');
Route::resource('/dashboard/posts', DashboardPostController::class)->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/dashboard/confirm-payment/{id}', [DashboardController::class, 'confirmPayment'])->name('dashboard.confirmPayment');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
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
Route::get('/cek-max-table', function () {
    return 'Max table: ' . (session('maxTable') ?? 'Belum di-set');
});


//session
Route::get('/test-session', [QrCodeController::class, 'testSession']);
Route::get('/cek-session', function () {
    return session('tableNumber') ?? 'Session kosong';});

//BELUM JADI YAAA//punya brina
Route::get('/checkout/{table?}', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/store', [CheckoutController::class, 'storeCustomerData'])->name('checkout.storeCustomerData');
Route::get('/checkout/success/{uuid}', [CheckoutController::class, 'success'])->name('checkout.success');

//struk
Route::get('/receipt/{uuid}', [ReceiptController::class, 'show'])->name('receipt.show');
Route::get('/receipt/download/{uuid}', [ReceiptController::class, 'downloadReceipt'])->name('download.receipt');
