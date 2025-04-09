<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Post;
use App\Models\Qr;

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

        return view('qr-code', [
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

        return view('qr-code', [
            "title" => "QR Code Meja",
            "qrCodes" => $qrCodes,
            "jumlahMeja" => $jumlahMeja,
            "image" => "logocafe.png",
            "active" => "qr-code"
        ]);
    }

    public function redirectToMenu($table)
    {
        \Log::info('Nomor Meja dari URL:', ['table' => $table]);

        $maxTableRecord = Qr::where('key', 'max_table')->first();
        $maxTable = $maxTableRecord ? (int) $maxTableRecord->value : 0;

        if (!is_numeric($table) || $table < 1 || $table > $maxTable) {
            abort(404, 'Nomor meja tidak valid');
        }

        session(['tableNumber' => $table]);
        \Log::info('Nomor Meja Disimpan ke Session:', ['tableNumber' => session('tableNumber')]);

        return redirect()->route('menu', ['table' => $table]);
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
}
