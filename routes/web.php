<?php

use App\Models\Post;
use App\Models\Category;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route; //koneksi otomatis ke model
use App\Http\Controllers\CartController;

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

Route::get('/formmenu', function () {
    return view('formmenu', [
         "title" => "formmenu",
         "name" => "Sunset View Cafe",
         "email" => "sunsetviewcandisari@gmail.com",
         "image" => "logocafe.png"

    ]) ;
});

Route::get('/listmenu', function () {
    return view('listmenu', [
         "title" => "listmenu",
         "image" => "logocafe.png"
        ]) ;
});

Route::get('/menu', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']); //ditangkap ke PostController

Route::get('/categories', function(){
    return view('categories', [
        'title' => 'Post Categories',
        'active' => 'categories',
        'categories' => Category::all()
    ]);
});

// Route::get('/categories/{category:slug}', function(Category $category) {
//     return view('posts', [
//         'title' => "Post By Category :$category->name",
//         'posts' => $category->posts->load('category', 'author')
//     ]);
// });

Route::get('/categories/{category:slug}', function(Category $category){
    return view('category',[
        'title' => $category->name,
        'active' => 'categories',
        'posts' => $category->posts, //1 kategori punya banyak post (dari metod post ke model category)
        'category' => $category->name
    ]);
});

// Route::get('/authors/{author:username}', function(User $author) {
//     return view('posts', [
//         'title' => "Post By Author :$author->name",
//         'posts' => $author->posts->load('category', 'author')
//     ]);
// });

Route::get('/cart', function(){
    return view('cart', [
        'title' => 'Cart',
        'active' => 'cart',
        'posts' => Post::all() // Kirim semua kategori ke view
    ]);
});

// Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/remove/{posts_id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');