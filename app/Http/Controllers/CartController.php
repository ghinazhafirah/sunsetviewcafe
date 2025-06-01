<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Post;

class CartController extends Controller
{
    public function addToCart (Request $request)
    {  
        //ambil data dari request
        $postId = $request->input('post_id'); 
        $quantity = $request->input('quantity', 1); 
        $note = $request->input('note', ''); 

        $tableNumber = $request->input('table_number'); // Ambil table_number dari request, ini KRUSIAL

         // Ambil selectedCategory dan search dari query string yang dikirim dari halaman posts.show
        $selectedCategory = $request->query('selectedCategory');
        $search = $request->query('search');

        //ambil data menu berdasarkan ID
        $post = Post::find($postId);
        if (!$post) {
            return back()->with('error', 'Menu tidak ditemukan');
        }

        // Ambil order_id dari session (wajib konsisten di semua controller)
        $orderId = session('order_id');
        // dd($orderId);
        $token = session('_token');

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

        // return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
         return redirect()->route('menu', [
            'table' => $tableNumber,
            'selectedCategory' => $selectedCategory, // Teruskan nilai ini
            'search' => $search // Teruskan nilai ini
        ])->with('success', 'Menu berhasil ditambahkan ke cart!');
    }

    //  public function showCart($table)
    // {
    //     return view('cart', [
    //         'title' => 'Cart',
    //         'tableNumber' => $table,
    //     ]);
    // }

     public function showCart($table, Request $request) // Tambahkan Request $request
    {
        // Ambil selectedCategory dan search dari query string
        $tableNumber = $table;
        $selectedCategory = $request->query('selectedCategory');
        $search = $request->query('search');

        Log::info('showCart called. table:', ['table' => $table]);
        Log::info('showCart called. selectedCategory:', ['selectedCategory' => $selectedCategory]);
        Log::info('showCart called. search:', ['search' => $search]);

        return view('cart', [
            'title' => 'Cart',
            'tableNumber' => $table,
            'selectedCategory' => $selectedCategory, // Teruskan ke view
            'search' => $search, // Teruskan ke view
        ]);
    }

}
