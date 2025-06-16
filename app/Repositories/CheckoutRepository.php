<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CheckoutRepository
{
    /**
     * Fungsi untuk memproses checkout
     * Aksi yang akan dilakukan:
     * 1. Membuat dan menyimpan data pesanan (Order)
     * 2. Membuat dan menyimpan data item pesanan (OrderItem)
     * 3. Membuat dan menyimpan data pembayaran (Payment)
     * 4. Mengirim notifikasi email kepada pengguna
     * 5. Mengembalikan data pesanan yang telah dibuat
     * 
     * @param array $data
     * @return Order
     */
    public function processCheckout(array $data, ?string $snapToken, string $orderId): Order
    {
        try {
            // * 1. Siapkan mekanisme transaction untuk memastikan semua operasi berhasil atau tidak sama sekali
            DB::beginTransaction();

            // * 2. Siapkan variabel paymentType dan isCashPayment
            $paymentType = $data['payment_type'];
            $isCashPayment = $paymentType === 'cash';

            // * 2.1 Jika pembayaran menggunakan digital, buat token Midtrans
            $itemsForMidtrans = array_map(function ($item) {
                return [
                    'id'       => (string) $item['id'],
                    'price'    => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name'     => $item['title'],
                ];
            }, array_values($data['items']));

            // * 2.2 Hitung total harga untuk semua item
            $grossAmount = collect($itemsForMidtrans)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // * 2.3 Siapkan variabel kodeTransaction untuk identifikasi unik transaksi
            $kodeTransaction = Str::uuid();

            // * 2.4 Jika pembayaran menggunakan digital, buat token Midtrans
            $order = Order::create([
                'order_id' => $orderId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_whatsapp' => $data['customer_phone'] ?? null,
                'kode_transaction' => $kodeTransaction,
                'total_price' => $grossAmount,
                'payment_method' => $paymentType,
                'table_number' => $data['table'],
                'status' => 'pending',
            ]);

            // * 2.5 Log informasi order yang telah dibuat
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'data' => $data,
            ]);

            // * 3. Buat data OrderItem untuk setiap item yang ada di dalam data
            foreach ($data['items'] as $item) {
                $order->orderItems()->create([
                    'order_id' => $order->id,
                    'post_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'status' => 'pending',
                    'note' => $item['note'] ?? null,
                ]);

                // * 3.1 Log informasi item yang telah dibuat
                Log::info('Order item created successfully', [
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // * 4. Buat data Payment untuk menyimpan informasi pembayaran
            $order->payment()->create([
                'order_id' => $order->order_id,
                'snap_token' => $isCashPayment ? null : $snapToken,
                'transaction_id' => $isCashPayment ? null : 'not_available',
                'status' => 'pending',
                'payment_method' => $paymentType,
                'payment_response' => $isCashPayment ? null
                    : 'not_available',
            ]);

            // * 4.1 Log informasi pembayaran yang telah dibuat
            Log::info('Order items and payment created successfully', [
                'order_id' => $order->id,
                'items_count' => count($data['items']),
            ]);

            // * 5. Kirim notifikasi email kepada pengguna (opsional, bisa menggunakan queue)
            // TODO: Implementasikan pengiriman email notifikasi

            // * 6. Commit transaction jika semua operasi berhasil
            DB::commit();

            // * 7. Kembalikan data order yang telah dibuat
            return $order;
        } catch (Throwable $th) {
            // * 8. Rollback transaction jika terjadi error
            DB::rollBack();

            // * 8.1 Log error yang terjadi
            Log::error('Checkout processing failed', [
                'error' => $th->getMessage(),
                'data' => $data,
            ]);

            // * 8.2 Lempar kembali exception untuk ditangani di level controller
            throw $th;
        }
    }
}
