<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Post;

class CartController extends Controller
{
    public function addToCart (Request $request)
    {  
        // dd(session()->all());

        //ambil data dari request
        $postId = $request->input('post_id'); 
        $quantity = $request->input('quantity', 1); 
        $note = $request->input('note', ''); 

        //ambil data menu berdasarkan ID
        $post = Post::find($postId);
        if (!$post) {
            return back()->with('error', 'Menu tidak ditemukan');
        }

        // Ambil order_id dari session (wajib konsisten di semua controller)
        $orderId = session('order_id');
        $token = session('_token');
        // dd($token);

       // Kalau belum ada order_id di session, buat order baru
        if (!$orderId) {
            // Cek apakah ada order pending untuk meja ini
            $order = Order::where('table_number', $tableNumber)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();

            if ($order) {
                //pake order_id lama kalo masih pending
                $orderId = $order->kode_transaction ?? $order->order_id;
            } else {
                //kalo ga ya bikin order_id baru = + 1
                $nextNumber = Order::where('table_number', $tableNumber)->count() + 1;
                $orderId = 'ORD' . $tableNumber . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

                // Simpan ke tabel orders
                Order::create([
                    'order_id' => $orderId,
                    'table_number' => $tableNumber,
                    'status' => 'pending',
                ]);
             }
             // Simpan ke session!
             session(['order_id' => $orderId]); 
       }

        //cek -> Tambah atau update menu ke cart
        $cart = Cart::where('order_id', $orderId)
                    ->where('posts_id', $postId)
                    ->first();

        if ($cart) {
            //kalo ada , update jumlah & total
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
                'total_menu' => $cart->total_menu + ($post->price * $quantity),
                'note' => $note,
            ]);
        } else {
            //kalo ga ada, bikin baru
            Cart::create([
                'token' => $token,
                'order_id' => $orderId,
                'posts_id' => $post->id,
                'quantity' => $quantity,
                'total_menu' => $post->price * $quantity,
                'table_number' => $tableNumber,
                'note' => $note,
            ]);
        }

    return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
}


    public function showCart($table)
    {
        // dd(session()->all());

        session(['tableNumber' => $table]);
    
        // Ambil order_id dari session
        $orderId = session('order_id');


        // Jika tidak ada order_id di session, tampilkan cart kosong
        if (!$orderId) {
            return view('cart', [
                'title' => 'Cart',
                'cart' => [],
                'total' => 0,
                'active' => 'cart',
                'tableNumber' => $table,
                'orderId' => null,
            ]);
        }
    
        // Ambil hanya pesanan berdasarkan order_id yang sesuai
        $cartItems = Cart::where('order_id', $orderId)->with('post')->get();
        $subtotal = $cartItems->sum(fn($cart) => $cart->post->price * $cart->quantity);
        
        // Simpan total ke session
        session(['cart_total' => $subtotal]);
    
        \Log::info("Menampilkan cart untuk meja {$table} dengan order_id: {$orderId}");
        
        //tampilan cart
        return view('cart', [
            'title' => 'Cart',
            'cart' => $cartItems,
            'total' => $subtotal,
            'active' => 'cart',
            'tableNumber' => $table,
            'orderId' => $orderId, 
        ]);
    }
}
