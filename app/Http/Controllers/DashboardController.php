<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $orders = Order::latest()->get();

    //     // Hitung total CASH dan MIDTRANS yang sudah "paid" menggunakan QUERY LANGSUNG
    //     $totalCash = Order::where('payment_method', 'cash')->where('status', 'paid')->sum('total_price');
       
    //     $totalMidtrans = Order::where('payment_method', 'midtrans')->where('status', 'paid')->sum('total_price');
    //     $totalPemasukan = $totalCash + $totalMidtrans;

    //      // Ambil data per tanggal 1-31 untuk bulan dan tahun sekarang
    //     $days = collect(range(1, 31));

    //     $cashData = $days->map(function ($day) {
    //         return Order::where('payment_method', 'cash')
    //             ->where('status', 'paid')
    //             ->whereDay('created_at', $day)
    //             ->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year)
    //             ->sum('total_price');
    //     });

    //     $midtransData = $days->map(function ($day) {
    //         return Order::where('payment_method', 'midtrans')
    //             ->where('status', 'paid')
    //             ->whereDay('created_at', $day)
    //             ->whereMonth('created_at', now()->month)
    //             ->whereYear('created_at', now()->year)
    //             ->sum('total_price');
    //     });
        
    //     return view('dashboard.index', [
    //         "title" => "Dashboard",
    //         "image" => "logocafe.png",
    //         "orders" => $orders,
    //         "totalCash" => $totalCash,
    //         "totalMidtrans" => $totalMidtrans,
    //         "totalPemasukan" => $totalPemasukan,
    //         "cashData" => $cashData,
    //         "midtransData" => $midtransData,
    //   ]);        
    // }

    public function index(Request $request)
    {
        $orders = Order::latest()->get();

        // Ambil bulan dan tahun dari query string, atau gunakan bulan & tahun sekarang sebagai default
        $selectedMonth = $request->query('month', now()->format('m'));
        $selectedYear = $request->query('year', now()->year);

        // Total Cash & Midtrans sesuai bulan-tahun
        $totalCash = Order::where('payment_method', 'cash')
            ->where('status', 'paid')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->sum('total_price');

        $totalMidtrans = Order::where('payment_method', 'midtrans')
            ->where('status', 'paid')
            ->whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->sum('total_price');

        $totalPemasukan = $totalCash + $totalMidtrans;

        // Ambil data harian (1-31)
        $days = collect(range(1, 31));

        $cashData = $days->map(function ($day) use ($selectedMonth, $selectedYear) {
            return Order::where('payment_method', 'cash')
                ->where('status', 'paid')
                ->whereDay('created_at', $day)
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->sum('total_price');
        });

        $midtransData = $days->map(function ($day) use ($selectedMonth, $selectedYear) {
            return Order::where('payment_method', 'midtrans')
                ->where('status', 'paid')
                ->whereDay('created_at', $day)
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->sum('total_price');
        });

        return view('dashboard.index', [
            "title" => "Dashboard",
            "image" => "logocafe.png",
            "orders" => $orders,
            "totalCash" => $totalCash,
            "totalMidtrans" => $totalMidtrans,
            "totalPemasukan" => $totalPemasukan,
            "cashData" => $cashData,
            "midtransData" => $midtransData,
            "selectedMonth" => $selectedMonth,
            "selectedYear" => $selectedYear,
        ]);
    }


    // public function confirmPayment($id)
    // {
    //     $order = Order::findOrFail($id);
    //     if ($order->payment_method == 'cash' && $order->status == 'pending') {
    //         $order->update(['status' => 'paid']);
    //         return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    //     }
    //     return redirect()->back()->with('error', 'Transaksi tidak valid!');
    // }

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        // Ambil order beserta carts terkait dan relasi post
        $order = Order::with('carts.post')->where('uuid', $uuid)->firstOrFail();
        
        // Cek apakah relasi carts sudah ter-load
       //  dd($order->carts);  // Periksa apakah cartItems sudah berisi data
    
        return view('dashboard.show', [
            'image' => 'logocafe.png',
            'title' => 'Konfirmasi Pembayaran',
            'order' => $order,
            'cartItems' => $order->carts,  // Kirim data cartItems ke view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }    
    public function destroy($id)
    {
        $order = Order::find($id); // Gunakan find() dulu, bukan findOrFail()
        
        if (!$order) {
            return redirect('dashboard.index')->with('error', 'Transaksi tidak ditemukan!');
        }

        $order->delete(); // Hapus transaksi

        return redirect()->route('dashboard.index')->with('success', 'Transaksi berhasil dihapus!');
    }

}
