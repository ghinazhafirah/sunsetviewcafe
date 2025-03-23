<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Models\Transaction;

class ReceiptController extends Controller
{
    public function show($uuid)
    {
        // Ambil data transaksi berdasarkan UUID
        $transaction = Transaction::where('uuid', $uuid)->firstOrFail();

        return view('checkout.receipt', [
            'transaction' => $transaction,
            'title' => 'Struk Pembayaran' // ✅ Tambahkan title di sini
        ]);
        
        // Tampilkan view receipt.blade.php dengan data transaksi
        // return view('checkout.receipt', compact('transaction'));
    }

    public function downloadReceipt($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->firstOrFail();
        
        $pdf = PDF::loadView('checkout.receipt-pdf', compact('transaction'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Struk_Transaksi_' . $transaction->kode_transaction . '.pdf');
    }
}
