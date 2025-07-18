<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Livewire;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nomor meja dari request atau session
        $tableNumber = $request->input('table_number') ?? session('tableNumber');    // Ambil nomor meja dari request atau session

        //kalo ga ada no meja, tampil pesan error
        if (!$tableNumber) {
            \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        }
        
        // Simpan nomor meja ke session jika belum ada
        session(['tableNumber' => $tableNumber]);;       

        // Ambil order_id dari session (wajib konsisten di semua controller)
        $orderId = session('order_id');

         // Jika order_id tidak ditemukan, redirect
         if (!$orderId) {
            return back()->with('error', 'Order tidak ditemukan.');
        }

        // Ambil data cart berdasarkan order_id, termasuk relasi post
        $cart = Cart::where('order_id', $orderId)->with('post')->get();
      
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

        $tableNumber = Session::get('tableNumber', 'Tidak Diketahui'); // Ambil nomor meja dari session
        $orderId = session('order_id');

        // Validasi input pelanggan
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|digits_between:10,15', //hanya inputan angka
            'customer_email' => 'required|email:rfc,dns|regex:/@gmail\.com$/i', // Validasi email dengan @gmail.com
            'payment_method' => 'required|in:cash,digital', // Pastikan hanya cash atau digital
        ]);

         // Ambil data cart dari database berdasarkan order_id
         $cartItems = Cart::where('order_id', $orderId)->get();
         $subtotal = $cartItems->sum(fn($item) => $item->total_menu);

        // Simpan data pelanggan ke database (tabel orders)
        $order = Order::updateOrCreate(
            ['order_id' => $orderId], // kondisi pencarian
        
            [ // data yang akan diisi/update
                'customer_name' => $request->customer_name,
                'customer_whatsapp' => $request->customer_whatsapp,
                'customer_email' => $request->customer_email, // Tambahkan customer_email
                'total_price' => $subtotal,
                'payment_method' => $request->payment_method,
                'status' => 'pending', // selalu pending, tunggu notifikasi dari Midtrans
                'table_number' => $tableNumber,
            ]
        );
        
    // Setelah transaksi dibuat, baru update dengan kode transaksi yang sesuai
        $tanggal = date('d'); // Hari (misal: 13)
        $bulan = date('m'); // Bulan (misal: 03)
        $tahun = date('y'); // Tahun 2 digit (misal: 25)

        // Format kode transaksi
        $kodeTransaction = "SVC-{$tanggal}{$bulan}{$tahun}-{$order->id}";

        // Simpan kembali kode transaksi ke database
        $order->update([
            'kode_transaction' => $kodeTransaction
        ]);

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
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Buat Snap token untuk transaksi digital
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id,
                    'gross_amount' => $order->total_price,
                ],
                'customer_details' => [
                    'customer_name' => $request->customer_name,
                    'customer_whatsapp' => $request->customer_whatsapp,
                    'email' => $request->customer_email, // Tambahkan email ke Midtrans params
                ],
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
        }

        // Jika metode pembayaran cash, langsung lanjut ke halaman sukses
        return redirect()->route('checkout.success', ['uuid' => $order->uuid]);
    }

    public function success($uuid)
    {
         // Ambil detail order berdasarkan UUID
        $order = Order::where('uuid', $uuid)->firstOrFail(); // ✅ Benar

        return view('checkout.success', [
            'title' => 'Checkout Berhasil',
            'active' => 'checkout',
            'order' => $order,
        ]);
    }

    //CASH
    public function confirmPayment($id)
    {
        // Temukan order berdasarkan ID
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        $order->status = 'paid';
        $order->save();

     // ✅ Update status payment jadi 'paid' (khusus metode cash)
        $payment = Payment::where('order_id', $order->order_id)->first();
        if ($payment && $payment->payment_method === 'cash') {
            $payment->status = 'paid';
            $payment->paid_at = now();
            $payment->save();
        }

        Livewire::emit('paymentUpdated', $order->id); // Update status pesanan di halaman pelanggan

        return response()->json(['success' => 'Pembayaran dikonfirmasi']);
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
