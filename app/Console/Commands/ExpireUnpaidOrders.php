<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireUnpaidOrders extends Command
{
    // Nama dan deskripsi command
    protected $signature = 'orders:expire';
    protected $description = 'Hapus pesanan yang lebih dari 24 jam dan belum dibayar';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Cari pesanan dengan status 'pending' yang lebih dari 24 jam
        $orders = Order::where('status', 'pending')
                       ->where('created_at', '<', now()->subHours(24))
                       ->get();

        // Hapus setiap pesanan yang memenuhi kriteria
        foreach ($orders as $order) {
            $order->delete(); // Bisa juga $order->update(['status' => 'expired']);
            $this->info("Pesanan ID {$order->id} telah dihapus karena tidak dibayar.");
        }
    }
}