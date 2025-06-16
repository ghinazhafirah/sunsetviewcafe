<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Livewire;
// use Barryvdh\DomPDF\Facade\Pdf;
// use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
      public function index(Request $request)
    {
        // Ambil nomor meja dari request atau session
        $tableNumber = $request->input('table_number') ?? session('tableNumber');

        // Jika tidak ada nomor meja, tampilkan pesan error
        if (!$tableNumber) {
            Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        }

        // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $tableNumber]);

        // Ambil order_id dari session (wajib konsisten di semua controller)
        $orderIdSession = session('order_id'); // Menggunakan nama variabel berbeda agar tidak bentrok

        // Jika order_id tidak ditemukan, redirect
        if (!$orderIdSession) {
            return back()->with('error', 'Order tidak ditemukan.');
        }

        // Ambil data cart berdasarkan order_id, termasuk relasi post
        $cart = Cart::where('order_id', $orderIdSession)->with('post')->get();

        // Hitung total harga dari cart
        $subtotal = $cart->sum(fn($item) => $item->total_menu);

        return view('checkout.index', [
            "title" => "Checkout",
            "active" => "checkout",
            "cart" => $cart,
            "total" => $subtotal,
            'tableNumber' => $tableNumber, // Kirim ke view agar tetap ada
        ]);
    }

    public function storeCustomerData(Request $request)
    {
        $tableNumber = Session::get('tableNumber', 'Tidak Diketahui');
        $orderId = session('order_id');

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|digits_between:10,15',
            'customer_email' => ['required', 'email', 'max:255', 'ends_with:@gmail.com'],            
            'payment_method' => 'required|in:cash,digital',
        ]);

        $cartItems = Cart::where('order_id', $orderId)->get();
        $subtotal = $cartItems->sum(fn($item) => $item->total_menu);

        // Simpan data pelanggan ke database (tabel orders)
        $order = Order::updateOrCreate(
            ['order_id' => $orderId],
            [
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'customer_email' => $request->customer_email,
                'total_price' => $subtotal,
                'payment_method' => $request->payment_method,
                'status' => 'pending', // Status awal pending
                'table_number' => $tableNumber,
            ]
        );

        // Setelah transaksi dibuat, baru update dengan kode transaksi yang sesuai
        $tanggal = date('d');
        $bulan = date('m');
        $tahun = date('y');
        $kodeTransaction = "SVC-{$tanggal}{$bulan}{$tahun}-{$order->id}";

        $order->update(['kode_transaction' => $kodeTransaction]);

        // Simpan data pembayaran ke tabel payments (baik cash maupun digital)
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method' => $request->payment_method,
                'status' => 'pending', // Status awal untuk cash maupun digital
            ]
        );

        // Jika pembayaran digital (Midtrans)
        if ($request->payment_method === 'digital') {
   
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            try {
                // Buat Snap token untuk transaksi digital
                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_id,
                        'gross_amount' => $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_whatsapp,
                    ],
                    'item_details' => $cartItems->map(function ($item) { // Gunakan 'item_details' sesuai dokumentasi Midtrans
                        return [
                            'id' => (string) $item->post_id,
                            'name' => $item->post->title,
                            'price' => (int) $item->post->price, // <-- HARGA SATUAN DARI POST
                            'quantity' => (int) $item->quantity,
                        ];
                    })->toArray(),
                    'callbacks' => [
                        'finish' => route('checkout.success', ['uuid' => $order->uuid]),
                    ]
                ];

                $snapToken = Snap::getSnapToken($params);

                // Simpan snap_token ke tabel payments untuk transaksi digital
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'snap_token' => $snapToken,
                        'status' => 'pending', // Status masih pending untuk menunggu pembayaran
                    ]
                );

                // Kirim view baru untuk menampilkan Snap
                return view('checkout.midtrans', [
                    "title" => "Checkout",
                    "active" => "checkout",
                    'snapToken' => $snapToken,
                    'order' => $order,
                ]);

            } catch (\Exception $e) {
                // Log error untuk debugging
                Log::error("Error generating Midtrans Snap Token: " . $e->getMessage());
                // Kembalikan ke halaman sebelumnya dengan pesan error
                return back()->with('error', 'Terjadi kesalahan saat memulai pembayaran digital. Silakan coba lagi.');
            }
        }
        return redirect()->route('checkout.success', ['uuid' => $order->uuid]);
    }

    /**
     * Menampilkan halaman sukses setelah checkout.
     *
     * @param string $uuid
     * @return \Illuminate\View\View
     */
    public function success($order_id)
    {
        $order = Order::where('order_id', $order_id)->firstOrFail();

        // Hapus flag inisiasi pembayaran digital dan order_id setelah transaksi BERHASIL
        // Session::forget('digital_payment_initiated_' . $order->order_id);
        // Session::forget('order_id'); // Hapus order_id hanya jika pesanan benar-benar selesai/berhasil

        return view('checkout.success', [
            'title' => 'Checkout Berhasil',
            'active' => 'checkout',
            'order' => $order,
        ]);
    }

    /**
     * Mengkonfirmasi pembayaran cash secara manual (biasanya oleh admin/kasir).
     *
     * @param int $id Order ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPayment($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment || $payment->payment_method !== 'cash') {
            return response()->json(['error' => 'Hanya transaksi dengan metode pembayaran cash yang dapat dikonfirmasi secara manual.'], 403);
        }

        if ($order->status === 'completed' || $order->status === 'paid') {
            Log::info('Pembayaran cash untuk Order ID ' . $order->id . ' sudah berstatus ' . $order->status . '. Tidak perlu update lagi.');
            Livewire::emit('paymentUpdated', $order->id, 'already_paid');
            return response()->json(['success' => 'Pembayaran sudah dikonfirmasi sebelumnya.']);
        }

        $order->status = 'paid';
        $order->save();

        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();

        // Hapus flag inisiasi pembayaran digital (jika ada) dan order_id setelah pembayaran cash dikonfirmasi
        // Session::forget('digital_payment_initiated_' . $order->order_id);
        // Session::forget('order_id'); // Hapus order_id karena pesanan sudah selesai dibayar tunai

        if ($order->status === 'paid' && !empty($order->customer_email)) {
            // $this->sendReceiptEmail($order); // Uncomment jika sudah diimplementasikan
            Livewire::emit('paymentUpdated', $order->id, 'paid_cash_email_sent');
            Log::info('Struk email akan dikirim ke ' . $order->customer_email . ' untuk Order ID ' . $order->id);
        } else {
            Log::warning('Email customer tidak tersedia atau status order belum paid untuk Order ID ' . $order->id . '. Struk tidak dapat dikirim via email.');
            Livewire::emit('paymentUpdated', $order->id, 'paid_cash_no_email');
        }

        Livewire::emit('paymentUpdated', $order->id);

        return response()->json(['success' => 'Pembayaran cash berhasil dikonfirmasi']);
    }

      public function changePayment(Request $request)
    {

        $tableNumber = Session::get('tableNumber', 'Tidak Diketahui'); // Ambil nomor meja dari session
        $orderId = session('order_id');
        $token = session('_token');

        $order = Order::where('order_id', $orderId)->first();
        $order->update(['status' => 'cancelled']);

        //ambil data menu berdasarkan Order Id
        $cart = Cart::where('order_id', $orderId)->get();


        if ($cart->count() > 0) {
            $cekCountOrder = Cart::where('table_number', $tableNumber)
            ->groupBy('order_id')
            ->select('order_id')
            ->get()->count();

            $orderId = 'ORD' . $tableNumber . ($cekCountOrder + 1) . time();

            session(['order_id' => $orderId]);

            foreach ($cart as $key => $c) {
                 Cart::create([
                    'token' => $token,
                    'order_id' => $orderId,
                    'posts_id' => $c->posts_id,
                    'quantity' => $c->quantity,
                    'total_menu' => $c->total_menu,
                    'table_number' => $c->table_number,
                    'note' => $c->note,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

        } 

        return redirect()->route('menu', ['table' => $tableNumber]);
    }
}
