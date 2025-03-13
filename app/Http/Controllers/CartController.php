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
        // dd($request->all()); // Debugging
        \Log::info('Data yang diterima dalam addToCart:', $request->all());        // dd(session('table_number'), $request->table_number);
        // \Log::info('Request Data:', $request->all()); // Log semua data yang dikirim

        $tableNumber = $request->input('table_number') ?? session('tableNumber');    

       // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $tableNumber]);
        
        // dd($tableNumber);


         // Pastikan nomor meja yang diterima valid (misalnya hanya angka)
        if (!is_numeric($tableNumber)) {
            \Log::warning('Nomor meja tidak valid: ' . $tableNumber);
            return back()->with('error', 'Nomor meja tidak valid.');
        }
        
        \Log::info('Nomor Meja dari Request:', ['tableNumber' => $tableNumber]);
    
        // Ambil data menu berdasarkan ID
        $postId = $request->input('post_id');
        $post = Post::find($postId);
        
        if (!$post) {
            \Log::error('Menu dengan ID ' . $postId . ' tidak ditemukan.');
            return back()->with('error', 'Menu tidak ditemukan');
        }
        // dd($post);

        $cart = Cart::where('table_number', $tableNumber)
            ->where('posts_id', $post->id)
            ->first();

        if ($cart) {
            // Jika item sudah ada, update jumlah dan total harga
            $cart->increment('jumlah_menu', 1);
            $cart->increment('total_menu', $post->harga);
        } else {
            // Jika item belum ada, buat item baru, simpan database
            Cart::create([
                'pesenan_id' => 8, // Sesuaikan dengan sistem pesananmu
                'posts_id' => $post->id,
                'jumlah_menu' => 1,
                'total_menu' => $post->harga,
                'table_number' => $tableNumber
            ]);
        }
        //  dd($cart);
         \Log::info('Menu berhasil ditambahkan ke Cart dengan Nomor Meja:', ['tableNumber' => $tableNumber]);

         return redirect()->route('menu', ['tableNumber' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
      } 
         
    // public function showCart()
    // {
    //    $tableNumber = session('tableNumber');
     
    //    if (!$tableNumber) {
    //     \Log::warning('Nomor meja tidak ditemukan saat membuka cart.');
    //     return redirect()->route('menu', ['tableNumber' => 1])->with('error', 'Nomor meja tidak ditemukan.');
    //    }

    //    // Ambil item cart hanya untuk nomor meja saat ini
    //    $cartItems = Cart::where('table_number', $tableNumber)->with('post')->get();
    //    \Log::info('isi Cart:', $cartItems->toArray()); // Debugging untuk cek isi cart
    //    $subtotal = $cartItems->sum(fn($cart) => $cart->post->harga * $cart->jumlah_menu);

    //     return view('cart', [
    //         'title' => 'Cart',
    //         'cart' => $cartItems,
    //         'total' => $subtotal, // Kirim subtotal ke view
    //         'active' => 'cart',
    //         'tableNumber' => $tableNumber
    //     ]);
    // }
    public function showCart($tableNumber = null)
    {
        // Ambil nomor meja dari session jika tidak ada di parameter URL
        if (!$tableNumber) {
            $tableNumber = session('tableNumber');
        }

        \Log::info('Nomor Meja saat membuka cart:', ['tableNumber' => $tableNumber]);

        // Jika tetap tidak ada, redirect ke menu dengan pesan error
        if (!$tableNumber) {
            \Log::warning('Nomor meja tidak ditemukan saat membuka cart.');
            return redirect()->route('menu')->with('error', 'Nomor meja tidak ditemukan.');
        }

        // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $tableNumber]);

        // Ambil item cart hanya untuk nomor meja saat ini
        $cartItems = Cart::where('table_number', $tableNumber)->with('post')->get();
        $subtotal = $cartItems->sum(fn($cart) => $cart->post->harga * $cart->jumlah_menu);

        return view('cart', [
            'title' => 'Cart',
            'cart' => $cartItems,
            'total' => $subtotal,
            'active' => 'cart',
            'tableNumber' => $tableNumber // Kirim ke view agar bisa ditampilkan
        ]);
    }

}
