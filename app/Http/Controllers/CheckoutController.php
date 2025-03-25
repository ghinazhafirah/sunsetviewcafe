<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Livewire\Livewire;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
         // Ambil nomor meja dari request atau session
         $tableNumber = $request->input('table_number') ?? session('tableNumber');    // Ambil nomor meja dari request atau session

         // Simpan nomor meja ke session jika belum ada
         session(['tableNumber' => $tableNumber]); 
         
         // Simpan nomor meja ke session jika belum ada
          if (!$tableNumber) {
             \Log::warning('Nomor meja tidak ditemukan dalam request maupun session.');
             return back()->with('error', 'Nomor meja tidak ditemukan.');
         }

        // Ambil nomor meja dari session (dari halaman cart)
        $tableNumber = session('tableNumber');

        // Ambil data cart dari database berdasarkan nomor meja
        // $cart = \App\Models\Cart::where('table_number', $tableNumber)->with('post')->get();
        $orderId = session("order_id_{$tableNumber}") ?? null;
        if (!$orderId) {
            return back()->with('error', 'Order tidak ditemukan.');
        }

        $cart = Cart::where('order_id', $orderId)->with('post')->get();


        // Hitung total harga berdasarkan database, bukan session
        $subtotal = $cart->sum(fn($cartItem) => $cartItem->total_menu);

        return view('checkout.index', [
            "title" => "Checkout",
            "active" => "checkout",
            "cart" => $cart,
            "total" => $subtotal,
            "tableNumber" => $tableNumber, // Kirim ke view agar tetap ada
        ]);        
    }

    public function storeCustomerData(Request $request)
    {
        $tableNumber = Session::get('tableNumber', 'Tidak Diketahui'); // Ambil nomor meja dari session

        // Validasi input pelanggan
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|digits_between:10,15', //hanya inputan angka
            'payment_method' => 'required|in:cash,digital', // Pastikan hanya cash atau digital
        ]);

         // Ambil data cart dari sesi
        $cart = collect(Session::get('cart', []));
        $subtotal = $cart->sum(fn($cartItem) => $cartItem['total_menu']); // Gunakan array notation

        // Simpan data pelanggan ke database (tabel transactions)
        $orderId = session("order_id_{$tableNumber}");
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_whatsapp' => $request->customer_whatsapp,
            'total' => $subtotal, // Set default price (nanti bisa diupdate)
            'payment_method' => $request->payment_method, // Cash atau Digital
            'status' => $request->payment_method == 'cash' ? 'pending' : 'paid', // Status awal
            'table_number' => $tableNumber, // Simpan nomor meja dalam transaksi
            'order_id' => $orderId, // Pastikan order_id digunakan
        ]);

        // **Setelah transaksi dibuat, baru update dengan kode transaksi yang sesuai**
        $tanggal = date('d'); // Hari (misal: 13)
        $bulan = date('m'); // Bulan (misal: 03)
        $tahun = date('y'); // Tahun 2 digit (misal: 25)

        // Format kode transaksi
        $kodeTransaction = "SVC-{$tanggal}{$bulan}{$tahun}-{$order->id}";

        // Simpan kembali kode transaksi ke database
        $order->update([
            'kode_transaction' => $kodeTransaction
        ]);

        // Hapus cart dari sesi setelah checkout
        session()->forget("order_id_{$tableNumber}");
        // Session::forget('cart');


        //Jika Jika metode pembayaran digital, arahkan ke Midtrans
        // if ($request->payment_method == 'digital') {
        //     // Arahkan ke Midtrans
        //     return redirect()->route('midtrans.payment', ['id' => $transaction->id]);
        // }

        // Jika cash, arahkan ke halaman sukses
        return redirect()->route('checkout.success', ['uuid' => $order->uuid]);
        return redirect()->route('cart', ['table' => $tableNumber])->with('success', 'Pesanan berhasil diproses!');
    }   

    public function success($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail(); // ✅ Benar

        return view('checkout.success', [
            'title' => 'Checkout Berhasil',
            'order' => $order
        ]);
    }

    public function confirmPayment($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        $order->status = 'paid';
        $order->save();

        Livewire::emit('paymentUpdated', $order->id); // Update status pesanan di halaman pelanggan

        return response()->json(['success' => 'Pembayaran dikonfirmasi']);
    }
}
