<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $orderId = session('order_id'); // Ambil order_id dari session

        if (!$orderId) {
            return back()->with('error', 'Order tidak ditemukan.');
        }

        // Ambil order berdasarkan order_id (bukan id numerik)
        $order = Order::where('order_id', $orderId)->firstOrFail();

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat Snap Token dengan order_id + timestamp agar unik untuk Midtrans
        $midtransOrderId = $order->order_id . '-'; 


        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_whatsapp,
            ],
            'callbacks' => [
                'finish' => route('checkout.success', ['uuid' => $order->uuid]),
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan atau update data pembayaran
        Payment::updateOrCreate(
            ['order_id' => $order->order_id], // Konsisten: pakai order_id string (mis. ORD31)
            [
                'snap_token' => $snapToken,
                'payment_method' => 'digital',
                'status' => 'pending',
                'uuid' => Str::uuid()->toString(), // Tambahkan ini
            ]
        );

        // Update status order
        $order->status = 'pending';
        $order->save();

        return response()->json(['snap_token' => $snapToken]);
    }

    public function callback(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;

        // Ambil notifikasi dari Midtrans
        $notif = new Notification();
        \Log::info('Midtrans notification', [
            'order_id' => $notif->order_id,
            'transaction_status' => $notif->transaction_status,
            'payment_type' => $notif->payment_type
        ]);
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $fraud = $notif->fraud_status;

        // Ambil order_id asli (tanpa -timestamp)
        $orderId = explode('-', $notif->order_id)[0];
       \Log::info("Processing order: {$orderId}");

        // Cari order
        $order = Order::where('order_id', $orderId)->first();
        if (!$order) {
            \Log::error("Order not found for callback: {$orderId}");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Cari pembayaran berdasarkan order_id string
        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) {
            \Log::error("Payment not found for order: {$orderId}");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        \Log::info("Found order and payment", ['order_id' => $order->id, 'payment_id' => $payment->id]);

        // Update status berdasarkan status Midtrans
        switch ($transaction) {
            case 'capture':
            case 'settlement':
                $payment->status = 'paid';
                $payment->paid_at = now();
                $order->status = 'paid';
                \Log::info("Payment marked as paid", ['order_id' => $orderId, 'status' => 'paid']);
                break;

            case 'cancel':
            case 'deny':
            case 'expire':
                $payment->status = 'failed';
                $order->status = 'failed';
                \Log::info("Payment marked as failed", ['order_id' => $orderId, 'status' => 'failed', 'reason' => $transaction]);
                break;

            case 'pending':
                $payment->status = 'pending';
                $order->status = 'pending';
                \Log::info("Payment remains pending", ['order_id' => $orderId]);
                break;

            default:
                \Log::warning("Unhandled transaction status: {$transaction}", ['order_id' => $orderId]);
                break;
        }

        // Simpan respon dan status
        $payment->payment_response = json_encode($notif);
        $payment->save();
        $order->save();

        \Log::info("Payment callback processed successfully", ['order_id' => $orderId, 'status' => $payment->status]);
        return response()->json(['message' => 'Payment status updated']);
    }

    public function show($order_id)
    {
        // Ambil order dan relasi payment langsung
        $order = Order::with('payment')->where('order_id', $order_id)->firstOrFail();
        $payment = $order->payment;

        return view('payments.show', compact('order', 'payment'));
    }
}