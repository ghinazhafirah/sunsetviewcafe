<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show($uuid)
    {
        // dd(session()->all());
       // Ambil data transaksi beserta relasi items dan post (menu)
    $order = Order::where('uuid', $uuid)->firstOrFail();

    // Ambil order_id dari data order
    $orderId = $order->order_id;

       // Ambil semua item cart berdasarkan order_id
       $cartItems = Cart::with('post')
       ->where('order_id', $orderId)
       ->get();

    // dd($cart);
        return view('checkout.receipt', [
            'order' => $order,
            'cartItems' => $cartItems,
            'title' => 'Struk Pembayaran' // ✅ Tambahkan title di sini
        ]);
        
        // Tampilkan view receipt.blade.php dengan data transaksi
        // return view('checkout.receipt', compact('order'));
    }

    public function downloadReceipt($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $orderId = $order->order_id;

        $cartItems = Cart::with('post')
        ->where('order_id', $orderId)
        ->get();

        $customPaper = [0, 0, 226.77, 600]; // Width: 80mm, Height: ±210mm (dalam points)

        $pdf = PDF::loadView('checkout.receipt-pdf', [
            'order' => $order,
            'cartItems' => $cartItems,
            'title' => 'Struk Pembayaran' // ✅ Tambahkan title di sini
        ])->setPaper($customPaper, 'portrait');

        return $pdf->download('Struk_Transaksi_' . $order->kode_transaction . '.pdf');
    }
}
