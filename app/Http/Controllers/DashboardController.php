<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama dengan ringkasan data dan chart penjualan.
     * Juga menangani filter bulan/tahun untuk data chart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Data untuk tabel transaksi (di-paginate)
        $orders = Order::latest()->paginate(); // Menggunakan paginate(5) sesuai permintaan Anda

        // Ambil bulan dan tahun dari query string (input GET), atau gunakan bulan & tahun sekarang sebagai default
        // Ini digunakan untuk filter chart dan total pemasukan yang difilter
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Hitung jumlah hari dalam bulan dan tahun yang dipilih
        $daysInMonth = Carbon::create($selectedYear, $selectedMonth, 1)->daysInMonth;

        // Buat koleksi hari dari 1 hingga jumlah hari dalam bulan
        $days = collect(range(1, $daysInMonth));

        // --- Data untuk Chart Harian (Pemasukan per Hari) ---
        $cashData = $days->map(function ($day) use ($selectedMonth, $selectedYear) {
            return Order::where('payment_method', 'cash')
                ->where('status', 'paid')
                ->whereDay('created_at', $day)
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->sum('total_price');
        });

        $midtransData = $days->map(function ($day) use ($selectedMonth, $selectedYear) {
            return Order::where('payment_method', 'digital')
                ->where('status', 'paid')
                ->whereDay('created_at', $day)
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->sum('total_price');
        });

        // --- Metrik Ringkasan Pemasukan ---

        // Total Penjualan Tunai Bulan Ini (sesuai bulan & tahun yang dipilih untuk filter chart)
        $totalCash = Order::where('payment_method', 'cash')
            ->where('status', 'paid')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->sum('total_price');

        // Total Penjualan Digital Bulan Ini (sesuai bulan & tahun yang dipilih untuk filter chart)
        $totalMidtrans = Order::where('payment_method', 'digital')
            ->where('status', 'paid')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->sum('total_price');

        // Total Pemasukan untuk bulan dan tahun yang dipilih (gabungan cash dan digital)
        // Ini adalah total yang akan berubah jika filter bulan/tahun grafik diubah
        $totalPemasukanBerdasarkanFilter = $totalCash + $totalMidtrans;


        // ************************************************
        // Metrik BARU: Total Penjualan untuk Bulan Saat Ini (terlepas dari filter)
        // Ini yang Anda inginkan agar selalu menampilkan total untuk bulan real-time saat ini
        $totalPemasukanBulanIni = Order::where('status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');
        // ************************************************


        // Total Penjualan Hari Ini (Cash + Digital, yang sudah 'paid')
        $totalHariIni = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'paid')
            ->sum('total_price');

        // Total Pemasukan Kumulatif Keseluruhan (Cash + Digital, dari awal hingga akhir)
        $totalKumulatifKeseluruhan = Order::where('status', 'paid')
            ->sum('total_price');

        // Ambil data transaksi yang sedang berjalan (status 'pending' atau 'challenge')
        $ongoingTransactions = Order::whereIn('status', ['pending', 'challenge'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', [
            "title" => "Dashboard",
            "image" => "logocafe.png",
            'active' => 'dashboard',
            "orders" => $orders,
            // Total Pemasukan berdasarkan filter bulan/tahun (untuk chart)
            "totalCash" => $totalCash,
            "totalMidtrans" => $totalMidtrans,
            "totalPemasukanFilter" => $totalPemasukanBerdasarkanFilter, // Nama variabel diubah agar jelas

            // Total Pemasukan untuk bulan real-time saat ini
            "totalPemasukanBulanIni" => $totalPemasukanBulanIni, // Variabel baru

            "totalHariIni" => $totalHariIni,
            "cashData" => $cashData->toArray(),
            "midtransData" => $midtransData->toArray(),
            "selectedMonth" => $selectedMonth,
            "selectedYear" => $selectedYear,
            'days' => $days->toArray(),
            'daysInMonth' => $daysInMonth,
            'totalKumulatifKeseluruhan' => $totalKumulatifKeseluruhan,
            'ongoingTransactions' => $ongoingTransactions,
        ]);
    }

    /**
     * Mengkonfirmasi pembayaran cash untuk order tertentu.
     *
     * @param string $id ID dari order.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);

        if (($order->payment_method == 'cash' || $order->payment_method == null) && $order->status == 'pending') {
            $order->update(['status' => 'paid', 'payment_method' => 'cash']);
            return redirect()->back()->with('success', 'Pembayaran cash berhasil dikonfirmasi!');
        }

        return redirect()->back()->with('error', 'Transaksi tidak valid atau sudah dibayar!');
    }

    /**
     * Menampilkan detail transaksi berdasarkan UUID.
     * Digunakan untuk melihat item-item dalam order (carts) dan detail produk terkait (post).
     *
     * @param string $uuid UUID dari order.
     * @return \Illuminate\View\View
     */
    public function show($uuid)
    {
        $order = Order::with('carts.post')->where('uuid', $uuid)->firstOrFail();

        return view('dashboard.show', [
            'image' => 'logocafe.png',
            'title' => 'Detail Transaksi',
            'order' => $order,
            'orderItems' => $order->orderItems,
        ]);
    }

    /**
     * Menghapus order dari database.
     *
     * @param string $id ID dari order yang akan dihapus.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->route('dashboard.index')->with('error', 'Transaksi tidak ditemukan!');
        }

        $order->delete();

        return redirect()->route('dashboard.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
}