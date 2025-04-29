<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderTable extends Component
{
    use WithPagination;

    public $totalCash;
    public $totalMidtrans;
    public $totalPemasukan;

    public function mount()
    {
        // Hitung total cash dan midtrans yang sudah "paid"
        $this->totalCash = Order::where('payment_method', 'cash')->where('status', 'paid')->sum('total_price');
        $this->totalMidtrans = Order::where('payment_method', 'midtrans')->where('status', 'paid')->sum('total_price');
        // Total pemasukan adalah penjumlahan keduanya
        $this->totalPemasukan = $this->totalCash + $this->totalMidtrans;
    }

    public function render()
    {
        return view('livewire.order-table', [
            'orders' => Order::latest()->paginate(10) // Ambil data terbaru
        ]);
    }
}