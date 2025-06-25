<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Repositories\CheckoutRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;
use Illuminate\Validation\ValidationException;

class ApiPaymentController extends Controller
{
    protected CheckoutRepository $checkoutRepository;

    public function __construct(CheckoutRepository $checkoutRepository)
    {
        $this->checkoutRepository = $checkoutRepository;
    }

    public function processPayment(Request $request): JsonResponse
    {
        try {
            // * 1. Validasi input dari request
            $validatedData = $request->validate([
                'table' => 'required|integer',
                'payment_type' => 'required|string|in:cash,digital',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
                'items.*.note' => 'nullable|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.title' => 'required|string',
            ]);

            // * 2. Siapkan order id untuk transaksi
            $orderId = 'ORDER-' . $validatedData['table'] . '-' . time();

            // * 3. Jika pembayaran menggunakan cash, langsung simpan data transaksi
            $paymentType = $validatedData['payment_type'];
            if ($paymentType === 'cash') {
                // * 4. Proses penyimpanan data transaksi
                $order = $this->checkoutRepository->processCheckout($validatedData, null, $orderId);

                // * 5. Kembalikan response sukses
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully.',
                    'data' => $order,
                ]);
            }

            // * 6. Jika pembayaran menggunakan digital, buat token Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $itemsForMidtrans = array_map(function ($item) {
                return [
                    'id'       => (string) $item['id'],
                    'price'    => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name'     => $item['title'],
                ];
            }, array_values($validatedData['items']));

            $grossAmount = collect($itemsForMidtrans)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $transactionData = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $itemsForMidtrans,
                'customer_details' => [
                    'first_name' => $validatedData['customer_name'],
                    'email'      => $validatedData['customer_email'],
                    'phone'      => $validatedData['customer_phone'] ?? null,
                ],
                'callbacks' => [
                    'finish' => route('checkout.success', ['order_id' => $orderId]),
                ],
            ];

            $snapToken = Snap::getSnapToken($transactionData);

            // * 7. Akhiri proses pembayaran digital dengan menjalankan payment
            $order = $this->checkoutRepository->processCheckout($validatedData, $snapToken, $orderId);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $transactionData['transaction_details']['order_id'],
                'gross_amount' => $transactionData['transaction_details']['gross_amount'],
                'order' => $order,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payment token: ' . $e->getMessage(),
            ], 500);
        }
    }
}
