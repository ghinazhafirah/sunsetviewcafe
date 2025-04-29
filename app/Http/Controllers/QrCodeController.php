<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Post;
use App\Models\Qr;
use App\Models\Order;
use App\Models\Cart;

class QrCodeController extends Controller
{
    //halaman form masukin jumlah meja
    public function showQrForm()
    {
        // Ambil jumlah meja terakhir dari database
        $maxTableRecord = Qr::where('key', 'max_table')->first();
        $jumlahMeja = $maxTableRecord ? (int) $maxTableRecord->value : null;

        $qrCodes = [];

        // Jika sudah pernah generate, buat ulang QR Code
        if ($jumlahMeja) {
            for ($i = 1; $i <= $jumlahMeja; $i++) {
                $url = route('menu.redirect', ['table' => $i]);
                $qrCodes[$i] = QrCode::size(200)->generate($url);
            }
        }

        return view('dashboard.qr.index', [
            "title" => "Generate QR Code Meja",
            "qrCodes" => $qrCodes,
            "jumlahMeja" => $jumlahMeja,
            "image" => "logocafe.png",
            "active" => "qr-code"
        ]);
    }

    public function generateQrCode(Request $request)
    {
       // Validasi input agar jumlah meja harus angka positif
        $request->validate([
            'jumlah_meja' => 'required|integer|min:1'
        ]);

        $jumlahMeja = $request->input('jumlah_meja');

        Qr::updateOrCreate(
            ['key' => 'max_table'],
            ['value' => $jumlahMeja]
        );

        // Buat QR Code untuk setiap meja
        $qrCodes = [];
        for ($i = 1; $i <= $jumlahMeja; $i++) {
            $url = route('menu.redirect', ['table' => $i]); // Ubah parameter agar sesuai dengan route menu
            \Log::info('URL untuk QR Code:', ['url' => $url]);
            $qrCodes[$i] = QrCode::size(200)->generate($url);
        }

        return view('dashboard.qr.index', [
            "title" => "QR Code Meja",
            "qrCodes" => $qrCodes,
            "jumlahMeja" => $jumlahMeja,
            "image" => "logocafe.png",
            "active" => "qr-code"
        ]);
    }

    public function redirectToMenu($table)
    {
        session()->flush(); //menghapus session sebelumnya jika ada (add by kocha 13042025)

        \Log::info('Nomor Meja dari URL:', ['table' => $table]);

        $maxTableRecord = Qr::where('key', 'max_table')->first();
        $maxTable = $maxTableRecord ? (int) $maxTableRecord->value : 0;

        if (!is_numeric($table) || $table < 1 || $table > $maxTable) {
            abort(404, 'Nomor meja tidak valid');
        }

        // Arahkan ke halaman menu dengan order_id di session
        return redirect()->route('menu', ['table' => $table]);
    }

    public function showMenu(Request $request)
    {
        
       $tableNumber = session('tableNumber');
        \Log::info('Nomor Meja saat membuka menu:', ['tableNumber' => $tableNumber]);

        // 1. Cek apakah sudah ada order_id di session
        if (!session()->has('order_id')) {
        // 2. Cari order yang masih pending untuk meja ini
        $activeOrder = Order::where('table_number', $tableNumber)
                            ->where('status', 'pending')
                            ->latest()                               
                            ->first();

        if ($activeOrder) {
            // 3. Jika ada, gunakan yang lama
            $order_id = $activeOrder->kode_transaction ?? $activeOrder->id;
        } else {
            // 4. Kalau belum ada, generate kode transaksi baru
            $latestOrder = Order::where('table_number', $tableNumber)->latest()->first();

            $nextNumber = $latestOrder ? intval(preg_replace('/[^0-9]/', '', $latestOrder->kode_transaction)) + 1 : 1;
            $kode_transaction = 'ORD' . $tableNumber . str_pad($nextNumber, 2, '0', STR_PAD_LEFT); // ex: ORD401, ORD402

            $order = Order::create([
                'uuid' => \Str::uuid(),
                'table_number' => $tableNumber,
                'customer_name' => 'Guest', // atau isi dari form jika tersedia
                'customer_whatsapp' => '08xxxxxx', // sementara default / bisa form juga
                'kode_transaction' => $kode_transaction,
                'total_price' => 0, // awalnya 0
                'status' => 'pending',
                'payment_method' => 'cash',
            ]);

            $order_id = $order->kode_transaction;
        }

        session(['order_id' => $order_id]);
    }

    // 5. Ambil semua post/menu
    $posts = Post::all();

        return view('posts', [
            "title" => "Menu",
            "posts" => $posts,
            // "images" => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
            "active" => "posts",
            "tableNumber" => $tableNumber,
            "order_id" => session('order_id')
        ]);
    }    
}