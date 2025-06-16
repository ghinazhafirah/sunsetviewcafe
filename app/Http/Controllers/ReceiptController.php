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
        // * 1. Cari order berdasarkan UUID
        $order = Order::where('uuid', $uuid)->firstOrFail();

        // * 2. Siapkan data order items
        $orderItems = $order->orderItems()->get();

        // * 2. Kembalikan view dengan data order
        return view('checkout.receipt', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Struk Pembayaran'
        ]);
    }

    public function downloadReceipt($uuid)
    {
        // * 1. Cari order berdasarkan UUID
        $order = Order::where('uuid', $uuid)->firstOrFail();

        // * 2. Siapkan data order items
        $orderItems = $order->orderItems()->get();

        // * 3. Buat PDF dengan ukuran kertas khusus (80mm x 210mm)
        $customPaper = [0, 0, 226.77, 600]; // Width: 80mm, Height: ±210mm (dalam points)

        // * 4. Gunakan view untuk membuat PDF
        $pdf = PDF::loadView('checkout.receipt-pdf', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Struk Pembayaran'
        ])->setPaper($customPaper, 'portrait');

        return $pdf->download('Struk_Transaksi_' . $order->kode_transaction . '.pdf');
    }
}
