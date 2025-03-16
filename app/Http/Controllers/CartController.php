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
         // Ambil data menu berdasarkan ID
         $postId = $request->input('post_id');
         $jumlahMenu = $request->input('jumlah_menu', 1); // Ambil jumlah dari Livewire
         $post = Post::find($postId);
        
        if (!$post) {
            \Log::error('Menu dengan ID ' . $postId . ' tidak ditemukan.');
            return back()->with('error', 'Menu tidak ditemukan');
        }

        // dd($request->all()); // Debugging
        \Log::info('Data yang diterima dalam addToCart:', $request->all());        

        $tableNumber = $request->input('table_number') ?? session('tableNumber');    // Ambil nomor meja dari request atau session

         // Simpan nomor meja ke session jika belum ada
        if ($tableNumber) {
            session(['tableNumber' => $tableNumber]);
        } else {
            \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        }

        \Log::info('Nomor Meja dari Request atau Session:', ['tableNumber' => $tableNumber]);

        // Pastikan nomor meja yang diterima valid (misalnya hanya angka)
        if (!is_numeric($tableNumber)) {
            \Log::warning('Nomor meja tidak valid: ' . $tableNumber);
            return back()->with('error', 'Nomor meja tidak valid.');
        }
        
        $cart = Cart::where('table_number', $tableNumber)
                    ->where('posts_id', $post->id)
                    ->first();

        if ($cart) {
            // Jika item sudah ada, update jumlah dan total harga
            $cart->increment('jumlah_menu', $jumlahMenu);
            $cart->increment('total_menu', $post->harga * $jumlahMenu);
        } else {
            // Jika item belum ada, buat item baru, simpan database
            Cart::create([
                'pesenan_id' => 8, // Sesuaikan dengan sistem pesananmu
                'posts_id' => $post->id,
                'jumlah_menu' => $jumlahMenu,
                'total_menu' => $post->harga * $jumlahMenu,
                'tablenumber' => $tableNumber
            ]);
        }
        //  dd($cart);
         \Log::info('Menu berhasil ditambahkan ke Cart dengan Nomor Meja:', ['tableNumber' => $tableNumber]);

         return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
      } 
         
    public function showCart($table)
    {
         // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $table]);

        \Log::info('Nomor Meja saat membuka cart:', ['tableNumber' => $table]);   

        // Ambil item cart hanya untuk nomor meja saat ini
        $cartItems = Cart::where('table_number', $table)->with('post')->get();
        $subtotal = $cartItems->sum(fn($cart) => $cart->post->harga * $cart->jumlah_menu);

        return view('cart', [
            'title' => 'Cart',
            'cart' => $cartItems,
            'total' => $subtotal,
            'active' => 'cart',
            'tableNumber' => $table // Kirim ke view agar bisa ditampilkan
        ]);
    }

}
