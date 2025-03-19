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

         // Ambil data menu berdasarkan ID
         $postId = $request->input('post_id');
         $jumlahMenu = $request->input('jumlah_menu', 1); // Ambil jumlah dari Livewire
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
        // if ($tableNumber) {
        //     session(['tableNumber' => $tableNumber]);
        // } else {
        //     \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
        //     return back()->with('error', 'Nomor meja tidak ditemukan.');
        // }

        // Pastikan nomor meja yang diterima valid (misalnya hanya angka)
        // if (!is_numeric($tableNumber)) {
        //     \Log::warning('Nomor meja tidak valid: ' . $tableNumber);
        //     return back()->with('error', 'Nomor meja tidak valid.');
        // }
        
        $cart = Cart::where('table_number', $tableNumber)
                    ->where('posts_id', $post->id)
                    ->first();
                               
        if ($cart) {
            $cart->update([
                'jumlah_menu' => $cart->jumlah_menu + $jumlahMenu,
                'total_menu' => $cart->total_menu + ($post->harga * $jumlahMenu),
                'note' => $note, // Simpan catatan jika ada perubahan
            ]);
        } else {
            // Jika item belum ada, buat item baru, simpan database
            Cart::create([
                'pesenan_id' => 8, // Sesuaikan dengan sistem pesananmu
                'posts_id' => $post->id,
                'jumlah_menu' => $jumlahMenu,
                'total_menu' => $post->harga * $jumlahMenu,
                'table_number' => $tableNumber,
                'note' => $note, // Simpan catatan pertama kali
            ]);
        }
        \Log::info('Note yang disimpan:', ['note' => $note]); // Pastikan ini muncul di log
        // $this->dispatch('cartUpdated');
         return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
      } 

      // Memperbarui catatan pelanggan di cart
    // public function updateNote(Request $request, $cartId)
    // {
    //     $cartItem = Cart::find($cartId);
    //     if (!$cartItem) {
    //         return back()->with('error', 'Item tidak ditemukan di cart.');
    //     }

    //     $note = $request->input('note', '');
    //     $cartItem->update([
    //         'note' => $note // Update catatan
    //     ]);

    //     \Log::info('Catatan diperbarui untuk cart ID ' . $cartId, ['note' => $cartItem->note]);

    //     return back()->with('success', 'Catatan berhasil diperbarui.');
    // }
         
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
            'tableNumber' => $table, // Kirim ke view agar bisa ditampilkan
            // 'note' => $note,
        ]);
    }

}
