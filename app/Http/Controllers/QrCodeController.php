<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Post;

class QrCodeController extends Controller
{
    public function generateQrCode(Request $request, $table)
    {
        \Log::info('Nomor Meja dari URL:', ['table' => $table]);
        
        if (!is_numeric($table)) {
            \Log::error('Nomor Meja yang diterima tidak valid: ' . $table);
        }  

        session(['tableNumber' => $table]);
        \Log::info('Nomor Meja Disimpan ke Session:', ['tableNumber' => session('tableNumber')]);

        // Redirect ke halaman menu agar session bisa digunakan
        return redirect()->route('menu', ['tableNumber' => $table]);
    }

    public function showMenu(Request $request)
    {
        $tableNumber = session('tableNumber');

        \Log::info('Nomor Meja saat membuka menu:', ['tableNumber' => $tableNumber]);

        // Ambil semua post dari database
        $posts = Post::all();

        $images = [
            'image1.jpg',
            'image2.jpg',
            'image3.jpg'
        ];

        return view('posts', [
            "title" => "Menu",
            "posts" => $posts,
            "images" => $images,
            "active" => "posts",
            "tableNumber" => $tableNumber
        ]);
    }

    public function showQrCode()
    {
        // Buat URL yang akan dipindai oleh pelanggan
        $tableNumbers = range(1, 20);
       
        // Buat QR Code untuk setiap meja
        $qrCodes = [];
        foreach ($tableNumbers as $tableNumber) {
            $url = route('menu', ['table' => $tableNumber]); 
            \Log::info('URL untuk QR Code:', ['url' => $url]);
            $qrCodes[$tableNumber] = QrCode::size(200)->generate($url);
        }

        return view('qr-code', [
            "title" => "QR Code Meja",
            "qrCodes" => $qrCodes,
            "active" => "qr-code"
        ]);
    }
}
