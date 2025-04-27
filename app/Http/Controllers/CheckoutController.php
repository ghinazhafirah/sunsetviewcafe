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
                'total_price' => $subtotal,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method == 'cash' ? 'pending' : 'paid',
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

        // Hapus cart dari sesi setelah checkout
        session()->forget("order_id");

        // Jika cash, arahkan ke halaman sukses
        return redirect()->route('checkout.success', ['uuid' => $order->uuid]);
        return redirect()->route('cart', ['table' => $tableNumber])->with('success', 'Pesanan berhasil diproses!');
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

    public function confirmPayment($id)
    {
        // Temukan order berdasarkan ID
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
