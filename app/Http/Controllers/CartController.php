<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Post;

class CartController extends Controller
{
    // punya ghina
    //menambahkan item ke cart
    // public function addToCart(Request $request)
    // {  
    //     \Log::info('Request data:', $request->all());

    //      $postId = $request->input('post_id'); // Ambil data menu berdasarkan ID
    //      $quantity = $request->input('quantity', 1); // Ambil quantity dari Livewire
    //      $note = $request->input('note', ''); // Ambil catatan pelanggan jika ada

    //      \Log::info('Note yang diterima:', ['note' => $note]); // Cek apakah nilai `note` masuk ke controller

    //      $post = Post::find($postId);
    //      if (!$post) {
    //         \Log::error('Menu dengan ID ' . $postId . ' tidak ditemukan.');
    //         return back()->with('error', 'Menu tidak ditemukan');
    //     }

    //     // Ambil nomor meja dari request atau session
    //     $tableNumber = $request->input('table_number') ?? session('tableNumber');    // Ambil nomor meja dari request atau session

    //     // Simpan nomor meja ke session jika belum ada
    //     session(['tableNumber' => $tableNumber]); 
        
    //     // Simpan nomor meja ke session jika belum ada
    //      if (!$tableNumber) {
    //         \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
    //         return back()->with('error', 'Nomor meja tidak ditemukan.');
    //     }
               
    //     $cart = Cart::where('table_number', $tableNumber)
    //                 ->where('posts_id', $post->id)
    //                 ->first();
                               
    //     if ($cart) {
    //         $cart->update([
    //             'quantity' => $cart->quantity + $quantity,
    //             'total_menu' => $cart->total_menu + ($post->price * $quantity),
    //             'note' => $note, // Simpan catatan jika ada perubahan
    //         ]);
    //     } else {
    //         // Jika item belum ada, buat item baru, simpan database
    //         Cart::create([
    //             'order_id' => 8, // Sesuaikan dengan sistem pesananmu
    //             'posts_id' => $post->id,
    //             'quantity' => $quantity,
    //             'total_menu' => $post->price * $quantity,
    //             'table_number' => $tableNumber,
    //             'note' => $note, // Simpan catatan pertama kali
    //         ]);
    //     }
    //     \Log::info('Note yang disimpan:', ['note' => $note]); // Pastikan ini muncul di log
    //      return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
    //   } 

    // public function showCart($table)
    // {
    //      // Simpan nomor meja ke session jika belum ada
    //     session(['tableNumber' => $table]);
    //     \Log::info('Nomor Meja saat membuka cart:', ['tableNumber' => $table]);   

    //     // Ambil item cart hanya untuk nomor meja saat ini
    //     $cartItems = Cart::where('table_number', $table)->with('post')->get();
    //     $subtotal = $cartItems->sum(fn($cart) => $cart->post->price * $cart->quantity);

    //      // Simpan total ke session agar bisa digunakan di checkout
    //     session(['cart_total' => $subtotal]);

    //     return view('cart', [
    //         'title' => 'Cart',
    //         'cart' => $cartItems,
    //         'total' => $subtotal,
    //         'active' => 'cart',
    //         'tableNumber' => $table, // Kirim ke view agar bisa ditampilkan
    //     ]);
    // }

    //PUNYA SABRINA ORDER_ID
    public function addToCart (Request $request)
    {  
        $postId = $request->input('post_id'); 
        $quantity = $request->input('quantity', 1); 
        $note = $request->input('note', ''); 

        $post = Post::find($postId);
        if (!$post) {
            return back()->with('error', 'Menu tidak ditemukan');
        }

        // Ambil nomor meja dari request atau session
        $tableNumber = $request->input('table_number') ?? session('tableNumber');    
        if (!$tableNumber) {
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        }

        // Cek apakah sudah ada order_id di session
        // if (!session()->has("order_id_{$tableNumber}")) {
        //     // Cek apakah ada pesanan sebelumnya
        //     $existingOrder = Cart::where('table_number', $tableNumber)->orderBy('created_at', 'desc')->first();
            
        //     if ($existingOrder) {
        //         $orderId = $existingOrder->order_id;
        //     } else {
        //         // Jika tidak ada pesanan sebelumnya, buat order_id baru
        //         $orderId = "ORD{$tableNumber}-" . now()->format('YmdHis');
        //     }

        //     // Simpan ke session
        //     session(["order_id_{$tableNumber}" => $orderId]);
        // } else {
        //     $orderId = session("order_id_{$tableNumber}");
        // }

        // Cek apakah ada order_id yang masih aktif (status pending) untuk meja ini
        $existingOrder = Cart::where('table_number', $tableNumber)
                            ->whereHas('order', function ($query) {
                                $query->where('status', 'pending'); // Order yang belum dibayar
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();

        if ($existingOrder) {
            $orderId = $existingOrder->order_id;
        } else {
            // Jika tidak ada order aktif, buat order baru
            $orderId = "ORD{$tableNumber}-" . now()->format('YmdHis');

            // Simpan ke tabel orders (jika ada tabel orders)
            Order::create([
                'order_id' => $orderId,
                'table_number' => $tableNumber,
                'status' => 'pending'
            ]);
        }

        // Periksa apakah item sudah ada di cart
        $cart = Cart::where('order_id', $orderId)
                    ->where('posts_id', $post->id)
                    ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
                'total_menu' => $cart->total_menu + ($post->price * $quantity),
                'note' => $note,
            ]);
        } else {
            Cart::create([
                'order_id' => $orderId,
                'posts_id' => $post->id,
                'quantity' => $quantity,
                'total_menu' => $post->price * $quantity,
                'table_number' => $tableNumber,
                'note' => $note,
            ]);
        }

         // Simpan ke session untuk caching
        session(["order_id_{$tableNumber}" => $orderId]);

        \Log::info("Item ditambahkan ke cart meja {$tableNumber} dengan order_id: {$orderId}");

        return redirect()->route('menu', ['table' => $tableNumber])->with('success', 'Menu berhasil ditambahkan ke cart!');
    }


    public function showCart($table)
    {
        session(['tableNumber' => $table]);
    
        // Ambil order_id dari session atau cari dari database
        if (!session()->has("order_id_{$table}")) {
            $latestOrder = Cart::where('table_number', $table)->orderBy('created_at', 'desc')->first();
            
            if ($latestOrder) {
                $orderId = $latestOrder->order_id;
            } else {
                // Jika tidak ada pesanan sebelumnya, buat order_id baru
                $orderId = "ORD{$table}-" . now()->format('YmdHis');
            }
    
            // Simpan ke session
            session(["order_id_{$table}" => $orderId]);
        } else {
            $orderId = session("order_id_{$table}");
        }
    
        // Ambil hanya pesanan berdasarkan order_id yang sesuai
        $cartItems = Cart::where('order_id', $orderId)->with('post')->get();
        $subtotal = $cartItems->sum(fn($cart) => $cart->post->price * $cart->quantity);
        
        // Simpan total ke session
        session(['cart_total' => $subtotal]);
    
        \Log::info("Menampilkan cart untuk meja {$table} dengan order_id: {$orderId}");
    
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
