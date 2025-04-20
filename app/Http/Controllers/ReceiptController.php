<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Models\Order;

class ReceiptController extends Controller
{
    public function show($uuid)
    {
        // Ambil data transaksi berdasarkan UUID
        $order = Order::where('uuid', $uuid)->firstOrFail();

        return view('checkout.receipt', [
            'order' => $order,
            'title' => 'Struk Pembayaran' // ✅ Tambahkan title di sini
        ]);
        
        // Tampilkan view receipt.blade.php dengan data transaksi
        // return view('checkout.receipt', compact('transaction'));
    }

    public function downloadReceipt($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        
        $pdf = PDF::loadView('checkout.receipt-pdf', compact('order'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Struk_Transaksi_' . $order->kode_transaction . '.pdf');
    }
}
