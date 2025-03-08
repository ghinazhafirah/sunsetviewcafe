<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Post;

class CartController extends Controller
{
    //menambahkan item ke cart
    public function addToCart(Request $request)
    {
        //ambil data post berdasarkan id
        $post = Post::findOrFail($request->posts_id); //memastikan item ada sebelum tambah ke cart
        $cart = Session::get('cart', []);

        //hitung total harga berdasarkan jumlah
        $jumlah_menu = $request->jumlah_menu ?? 1;
        $total_menu = $jumlah_menu * $post->harga;

        //data item yang akan ditambahkan ke cart
        $item = [
            'pesenan_id' => '8',
            'posts_id' => $post->id,
            'jumlah_menu' => $this->count,
            'total_menu' => $total_menu,
            'created_at' => date('Y-m-d H:i:s'),
        ];

    $cartCollection = collect($cart);
    $existingItem = $cartCollection->firstWhere('posts_id', $post->id);

    if ($existingItem) {
        // Jika item sudah ada, update jumlah & total harga
        $cart = $cartCollection->map(function ($cartItem) use ($post, $jumlah_menu) {
            if ($cartItem['posts_id'] == $post->id) {
                $cartItem['jumlah_menu'] += $jumlah_menu;
                $cartItem['total_menu'] += $jumlah_menu * $post->price;
            }
            return $cartItem;
        })->toArray();
    } else {
        // Jika item belum ada, tambahkan ke cart
        $cart[] = $item;
    }

    // Simpan kembali ke session
    Session::put('cart', $cart);

    return redirect()->back()->with('success', 'Item berhasil ditambahkan ke cart!');
}

    // Menampilkan halaman cart
    public function showCart()
    {
        // Ambil semua data cart dari database
         $cartItems = Cart::with('post')->get();

        //hitung subtotal
        $subtotal = $cartItems->sum(function($cart){
            return $cart->post->harga * $cart->jumlah_menu;
        });

        return view('cart', [
            'title' => 'Cart',
            'cart' => $cartItems,
            'total' => $subtotal, // Kirim subtotal ke view
            'active' => 'cart'
        ]);
    }
}

