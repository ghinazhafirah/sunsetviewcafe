<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $order = Order::latest()->get();
       
        return view('dashboard.index', [
            "title" => "Dashboard",
            "image" => "logocafe.png",
            "order" => $order
        ]);
        
    }

    // public function confirmPayment($id)
    // {
    //     $transaction = Transaction::findOrFail($id);
    //     $transaction->update([
    //         'status' => 'paid',
    //         'payment_method' => 'cash',
    //     ]);

    //     return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi!');

    // }

    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);
        if ($order->payment_method == 'cash' && $order->status == 'pending') {
            $order->update(['status' => 'paid']);
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }
        return redirect()->back()->with('error', 'Transaksi tidak valid!');
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
    public function show(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $transaction = Transaction::findOrFail($id);
    //     $transaction->delete();

    //     return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
     
        
    //     // Transcation::destroy($transaction->id);

    //     // return redirect('/dashboard')->with('success', 'Transaksi berhasil dihapus!');

    // }
    
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
