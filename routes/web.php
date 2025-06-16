<?php

use App\Models\Post;
use Livewire\Livewire;
use App\Models\Category;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\QrController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPostController;
use Illuminate\Support\Facades\Route; //koneksi otomatis ke model


Route::get('/', function () {       //halaman utama ketika url diakses
    return view('login.index', [          //ubah bagian view
        "title" => "login",
    ]);
});

Route::get('/cart', function () {
    return view('cart.index', [
        "title" => "checkout",
    ]);
});

//MENU
Route::get('/menu', [PostController::class, 'index']);
Route::get('posts/{post:slug}', [PostController::class, 'show']); //ditangkap ke PostController, maka slug yang diquery untuk dapet post yg uniqnya

Route::get('/categories', function () {
    return view('categories', [
        'title' => 'Post Categories',
        'active' => 'categories',
        'categories' => Category::all() //mengambil model dari kategori kita
    ]);
});

Route::get('/categories/{category:slug}', function (Category $category) {
    return view('category', [
        'title' => $category->name,
        'active' => 'categories',
        'posts' => $category->posts, //1 kategori punya banyak post (dari metod post ke model category)
        'category' => $category->name
    ]);
});

//LOGIN
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

//REGISTER
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

//QR untuk Admin Generate QR
Route::middleware('auth')->get('/dashboard/qr', [QrController::class, 'showQrForm'])->name('qr.form');
Route::middleware('auth')->post('/dashboard/generate-qr', [QrController::class, 'generateQrCode'])->name('generate.qr');
Route::get('/cek-max-table', function () {
    return 'Max table: ' . (session('maxTable') ?? 'Belum di-set');
});

//QR untuk Pelanggan/
Route::get('/meja/{table}', [QrController::class, 'redirectToMenu'])->name('menu.redirect'); // Akses menu berdasarkan meja

//DASHBOARD
Route::get('dashboard/posts/checkSlug', [DashboardPostController::class, 'checkSlug'])->middleware('auth');
Route::resource('/dashboard/posts', DashboardPostController::class)->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/{uuid}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::post('/dashboard/confirm-payment/{id}', [DashboardController::class, 'confirmPayment'])->name('dashboard.confirmPayment');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');

    // Export Excel
    Route::get('/export-orders', function () {
        return Excel::download(new OrderExport, 'data-transaksi.xlsx');
    })->name('dashboard.export.orders');
});

//QR Dashboard (Untuk Admin)
// Route::middleware('auth')->get('/dashboard/qr', function () {
//     return view('dashboard.qr.index', [
//         'title' => 'QR Code',
//         "image" => "logocafe.png",
//     ]);
// });


//MENU 
Route::get('/menu/{table?}', [PostController::class, 'index'])->name('menu');
Route::get('/menu/{table?}/{slug}', [PostController::class, 'show'])->name('post.show');

//menu juga tapi untuk file posts
Route::get('/posts/{slug}/{tableNumber?}', [PostController::class, 'show'])
    ->where('tableNumber', '[0-9]+')
    ->name('posts.show');

//CART BARU
Route::get('/cart/{table?}', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::match(['get', 'post'], '/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// * Rute baru untuk cart hasil refactor
Route::get('/cart-refactor/{tableNumber}', [CartController::class, 'index'])->name('cart.refactor.index');

//CART
Livewire::listen('addToCart', [CartController::class, 'addToCart']);

//session
Route::get('/test-session', [QrController::class, 'testSession']);
Route::get('/cek-session', function () {
    return session('tableNumber') ?? 'Session kosong';
});

//CHECKOUT//
Route::get('/checkout/{table?}', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/store', [CheckoutController::class, 'storeCustomerData'])->name('checkout.storeCustomerData');
Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/change-payment', [CheckoutController::class, 'changePayment'])->name('checkout.changePayment');

// Route untuk konfirmasi pembayaran oleh admin/kasir
Route::post('/checkout/confirm/{id}', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm');

// Route untuk AJAX Snap Token Midtrans
Route::get('/checkout/midtrans', [CheckoutController::class, 'checkout'])->name('checkout.midtrans');

Route::post('/checkout/token', [CheckoutController::class, 'getSnapToken'])->name('checkout.token');

//PAYMENT
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::post('/payments/callback', [PaymentController::class, 'callback']); // Midtrans callback
Route::get('/payments/{order_id}', [PaymentController::class, 'show'])->name('payments.show');


//STRUK//
Route::get('/receipt/{uuid}', [ReceiptController::class, 'show'])->name('receipt.show');
Route::get('/receipt/download/{uuid}', [ReceiptController::class, 'downloadReceipt'])->name('download.receipt');
