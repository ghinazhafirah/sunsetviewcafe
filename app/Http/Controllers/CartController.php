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
        \Log::info('Request data:', $request->all());

         $postId = $request->input('post_id'); // Ambil data menu berdasarkan ID
         $quantity = $request->input('quantity', 1); // Ambil quantity dari Livewire
         $note = $request->input('note', ''); // Ambil catatan pelanggan jika ada

         \Log::info('Note yang diterima:', ['note' => $note]); // Cek apakah nilai `note` masuk ke controller

         $post = Post::find($postId);
         if (!$post) {
            \Log::error('Menu dengan ID ' . $postId . ' tidak ditemukan.');
            return back()->with('error', 'Menu tidak ditemukan');
        }

        // Ambil nomor meja dari request atau session
        $tableNumber = $request->input('table_number') ?? session('tableNumber');    // Ambil nomor meja dari request atau session

         // Simpan nomor meja ke session jika belum ada
         if (!$tableNumber) {
            \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        }
               
        $cart = Cart::where('table_number', $tableNumber)
                    ->where('posts_id', $post->id)
                    ->first();
                               
        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
                'total_menu' => $cart->total_menu + ($post->harga * $quantity),
                'note' => $note, // Simpan catatan jika ada perubahan
            ]);
        } else {
            // Jika item belum ada, buat item baru, simpan database
            Cart::create([
                'order_id' => 8, // Sesuaikan dengan sistem pesananmu
                'posts_id' => $post->id,
                'quantity' => $quantity,
                'total_menu' => $post->harga * $quantity,
                'table_number' => $tableNumber,
                'note' => $note, // Simpan catatan pertama kali
            ]);
        }
        \Log::info('Note yang disimpan:', ['note' => $note]); // Pastikan ini muncul di log
        // $this->dispatch('cartUpdated');
         return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
      } 

    public function showCart($table)
    {
         // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $table]);
        \Log::info('Nomor Meja saat membuka cart:', ['tableNumber' => $table]);   

        // Ambil item cart hanya untuk nomor meja saat ini
        $cartItems = Cart::where('table_number', $table)->with('post')->get();
        $subtotal = $cartItems->sum(fn($cart) => $cart->post->harga * $cart->quantity);

        return view('cart', [
            'title' => 'Cart',
            'cart' => $cartItems,
            'total' => $subtotal,
            'active' => 'cart',
            'tableNumber' => $table, // Kirim ke view agar bisa ditampilkan
        ]);
    }
}
