<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function store()
    {
        // * Ambil order_id dari session
        $orderId = session('order_id');

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
        $midtransOrderId = $order->order_id;
        // $midtransOrderId = $order->order_id . '-' . time();

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

        $snapToken = Snap::getSnapToken($params);

        Log::info("Snap token berhasil dibuat", [
            'order_id' => $midtransOrderId,
            'snap_token' => $snapToken,
            'gross_amount' => $order->total_price,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_whatsapp
        ]);

        // * Simpan atau update data pembayaran
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
        // * Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;

        // * Log untuk mengetahui response dari Midtrans
        // * File logging bisa ditemukan di storage/logs/laravel.log
        Log::info(
            'Midtrans callback received',
            [
                'request' => $request->all()
            ]
        );

        // * Ambil notifikasi dari Midtrans        
        $notif = new Notification();

        // * Ambil transaction_status dari notifikasi
        $transactionStatus = $notif->transaction_status;

        // * Ambil requestOrderId dan transactionId dari request
        $requestOrderId = $request->input('order_id');
        $transactionId = $request->input('transaction_id');

        // * Lakukan switch case untuk menangani berbagai status transaksi
        switch ($transactionStatus) {
            case 'capture':
                break;
            case 'settlement':
                // * Menerima notifikasi settlement
                Log::info(
                    'Transaction captured or settled',
                    [
                        'request' => $request->all(),
                    ]
                );

                // * Ambil transactionId dan order_id dari response (JSON)
                $transactionId = $request->input('transaction_id');
                $requestOrderId = $request->input('order_id');

                // * Cari pembayaran berdasarkan transaction_id
                $payment = Payment::where('transaction_id', $transactionId)->first();

                // * Jika payment tidak ditemukan, log error dan hentikan proses
                if (!$payment) {
                    Log::error(
                        'Payment not found for settlement transaction',
                        [
                            'transaction_id' => $transactionId,
                            'request_order_id' => $requestOrderId, // Log the order_id from the request
                        ]
                    );
                    break; // Keluar dari switch case
                }

                // * Ambil order berdasarkan order_id di payment
                // $payment dijamin ada pada titik ini
                $order = Order::where('order_id', $payment->order_id)->first();

                // * Jika order ditemukan, update statusnya
                if ($order) {
                    $order->status = 'paid';
                    $order->save();

                    Log::info(
                        'Order status updated to paid',
                        [
                            'order_id' => $order->id, // Asumsi primary key order adalah 'id'
                            'transaction_id' => $transactionId
                        ]
                    );

                    // Update status payment
                    $payment->status = 'paid';
                    $payment->payment_response = $request->all();
                    // $payment->transaction_id sudah sesuai karena digunakan untuk mencari payment.
                    // Jika ada kebutuhan khusus untuk mengupdate dari request (misal, Midtrans mengirim ID yang berbeda untuk settlement),
                    // Anda bisa uncomment baris berikut:
                    // $payment->transaction_id = $transactionId; // atau $request->input('transaction_id')
                    $payment->save();

                    Log::info(
                        'Payment status updated to paid', // "and transaction_id saved" mungkin redundan jika tidak diubah
                        [
                            'payment_id' => $payment->id,
                            'order_id' => $order->id, // Gunakan $order->id
                            'transaction_id' => $payment->transaction_id // Gunakan transaction_id dari payment object
                        ]
                    );
                } else {
                    // * Jika order tidak ditemukan, log error
                    Log::error(
                        'Order not found for settlement transaction, but payment was found',
                        [
                            'payment_order_id' => $payment->order_id, // order_id dari tabel payment
                            'transaction_id' => $transactionId,
                            'request_order_id' => $requestOrderId // order_id dari request Midtrans
                        ]
                    );
                }

                // * Log
                Log::info('Transaction settled successfully', [
                    'payment' => $payment,
                    'order' => $order,
                    'orderItems' => $order->orderItems,
                ]);

                // * Kirim email ke customer
                $invoiceMail = new InvoiceMail(
                    payment: $payment,
                    order: $order,
                );
                Mail::to($order->customer_email)->send($invoiceMail);

                break;

            case 'cancel':
                break;

            case 'deny':
                break;

            case 'expire':
                Log::info('Transaction cancelled, denied, or expired', [
                    'order_id' => $notif->order_id,
                    'transaction_status' => $transactionStatus
                ]);
                break;

            case 'pending':
                // * Menerima notifikasi pending
                Log::info(
                    'Transaction pending notification received', // Clarified log message
                    [
                        'request' => $request->all(),
                    ]
                );

                // * Ambil order_id dan transaction_id dari request
                $requestOrderId = $request->input('order_id');
                $transactionId = $request->input('transaction_id');

                // * Cari order berdasarkan order_id dari request
                // Asumsi: tabel 'orders' memiliki kolom 'order_id' yang menyimpan ID order dari Midtrans.
                // Jika Anda menggunakan kolom lain atau jika 'order_id' adalah primary key, sesuaikan query ini.
                $order = Order::where('order_id', $requestOrderId)->first();

                // * Jika order tidak ditemukan, log error dan hentikan
                if (!$order) {
                    Log::error(
                        'Order not found for pending transaction',
                        [
                            'request_order_id' => $requestOrderId,
                            'transaction_id' => $transactionId, // Sertakan transaction_id jika ada
                        ]
                    );
                    break; // Keluar dari switch case
                }

                // * Order ditemukan, update statusnya
                $order->status = 'pending';
                $order->save();

                Log::info(
                    'Order status updated to pending',
                    [
                        'internal_order_id' => $order->id, // ID order internal (primary key)
                        'request_order_id' => $requestOrderId, // ID order dari Midtrans
                        'transaction_id' => $transactionId,
                    ]
                );

                // * Cari atau buat/update data pembayaran
                // Asumsi: tabel 'payments' memiliki kolom 'order_id' (foreign key ke orders.id)
                // dan Anda ingin menyimpan transaction_id dari Midtrans.
                $payment = Payment::where('order_id', $order->order_id)->first();

                if ($payment) {
                    // Payment ditemukan, update status dan transaction_id
                    $payment->status = 'pending';
                    $payment->transaction_id = $transactionId; // Simpan/Update transaction_id dari Midtrans
                    $payment->payment_response = $request->all();
                    $payment->save();

                    Log::info(
                        'Payment status updated to pending and transaction_id saved',
                        [
                            'payment_id' => $payment->id,
                            'internal_order_id' => $order->id,
                            'request_order_id' => $requestOrderId,
                            'transaction_id' => $payment->transaction_id, // Bisa juga $transactionId
                        ]
                    );
                } else {
                    // Payment tidak ditemukan.
                    // Tergantung logika bisnis Anda, Anda mungkin ingin membuat record payment baru di sini,
                    // atau ini mungkin sebuah kondisi error jika Anda selalu mengharapkan payment record sudah ada.

                    // Opsi 1: Buat record payment baru jika belum ada
                    /*
                    $newPayment = new Payment();
                    $newPayment->order_id = $order->id;
                    $newPayment->status = 'pending';
                    $newPayment->transaction_id = $transactionId;
                    $newPayment->amount = $request->input('gross_amount'); // Ambil jumlah dari request jika perlu
                    $newPayment->payment_type = $request->input('payment_type'); // Ambil tipe pembayaran
                    // Atur field lain yang relevan
                    $newPayment->save();

                    Log::info(
                        'New payment record created for pending transaction and transaction_id saved',
                        [
                            'payment_id' => $newPayment->id,
                            'internal_order_id' => $order->id,
                            'request_order_id' => $requestOrderId,
                            'transaction_id' => $transactionId,
                        ]
                    );
                    */

                    // Opsi 2: Log error jika Anda mengharapkan payment record sudah ada
                    Log::error(
                        'Payment record not found for pending transaction (Order was found and updated)',
                        [
                            'internal_order_id' => $order->id,
                            'request_order_id' => $requestOrderId,
                            'transaction_id' => $transactionId,
                        ]
                    );
                }

                // * Kirim email ke customer
                $invoiceMail = new InvoiceMail(
                    payment: $payment,
                    order: $order,
                );
                Mail::to($order->customer_email)->send($invoiceMail);

                break;
            default:
                Log::warning('Unhandled transaction status', [
                    'order_id' => $notif->order_id,
                    'transaction_status' => $transactionStatus
                ]);
                break;
        }

        // * Kembalikan response sukses
        return response()->json([
            'message' => 'Callback processed successfully'
        ]);

        /*
        // * Ambil orderId dari notifikasi Midtrans
        $orderId = $notif->order_id;

        // * Ambil order berdasarkan order_id
        $order = Order::where('order_id', $orderId)->first();

        // * Cari payment berdasarkan order dengan bantuan relasi
        $payment = $order->payment;

        // * Jika ditemukan, update kolom 'transaction_id' pada tabel 'payments'
        if ($payment) {
            $payment->transaction_id = $notif->transaction_id;
            $payment->save();

            Log::info(
                "Payment updated with transaction_id",
                [
                    'transaction_id' => $notif->transaction_id
                ]
            );
        } else {

            // * Jika tidak ditemukan, log error dan return response
            Log::error("Payment not found for order_id: {$orderId}");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        Log::info('Midtrans notification', [
            'order_id' => $notif->order_id,
            'transaction_status' => $notif->transaction_status,
            'payment_type' => $notif->payment_type
        ]);

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $fraud = $notif->fraud_status;

        // Ambil order_id asli (tanpa -timestamp)
        // $orderId = explode('-', $notif->order_id)[0];
        $orderId = $notif->order_id; // Karena order_id sudah unik dari awal

        Log::info("Processing order: {$orderId}");

        // Cari order
        $order = Order::where('order_id', $orderId)->first();
        if (!$order) {
            Log::error("Order not found for callback: {$orderId}");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Cari pembayaran berdasarkan order_id string
        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) {
            Log::error("Payment not found for order: {$orderId}");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        Log::info("Found order and payment", ['order_id' => $order->id, 'payment_id' => $payment->id]);

        // Update status berdasarkan status Midtrans
        switch ($transaction) {
            case 'capture':
            case 'settlement':
                $payment->status = 'paid';
                $payment->paid_at = now();
                $order->status = 'paid';
                Log::info("Payment marked as paid", ['order_id' => $orderId, 'status' => 'paid']);
                break;

            case 'cancel':
            case 'deny':
            case 'expire':
                $payment->status = 'failed';
                $order->status = 'failed';
                Log::info("Payment marked as failed", ['order_id' => $orderId, 'status' => 'failed', 'reason' => $transaction]);
                break;

            case 'pending':
                $payment->status = 'pending';
                $order->status = 'pending';
                Log::info("Payment remains pending", ['order_id' => $orderId]);
                break;

            default:
                Log::warning("Unhandled transaction status: {$transaction}", ['order_id' => $orderId]);
                break;
        }

        // Simpan respon dan status
        $payment->payment_response = json_encode($notif);
        $payment->save();
        $order->save();

        Log::info("Payment callback processed successfully", ['order_id' => $orderId, 'status' => $payment->status]);
        return response()->json(['message' => 'Payment status updated']);
        */
    }

    public function show($order_id)
    {
        // Ambil order dan relasi payment langsung
        $order = Order::with('payment')->where('order_id', $order_id)->firstOrFail();
        $payment = $order->payment;

        return view('payments.show', compact('order', 'payment'));
    }
}
