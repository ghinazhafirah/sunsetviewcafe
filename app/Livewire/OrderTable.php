<?php

namespace App\Livewire;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Order;

class OrderTable extends Component
{
    use WithPagination;

    public $search = ''; // Properti untuk pencarian
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

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination saat pencarian berubah
    }

    public function render()
    {
          // Filter data berdasarkan pencarian
            $orders = Order::query()
            // Terapkan kondisi filter berdasarkan metode pembayaran dan status.
            ->where(function ($query) {
                // Kondisi untuk pembayaran 'cash': status 'pending' ATAU 'paid'.
                $query->where('payment_method', 'cash')
                      ->whereIn('status', ['pending', 'paid']);
            })
            // Kondisi untuk pembayaran 'midtrans' (digital): status HANYA 'paid'.
            // Menggunakan orWhere untuk menggabungkan kondisi 'cash' dan 'midtrans'.
            ->orWhere(function ($query) {
                $query->where('payment_method', 'digital')
                      ->where('status', 'paid');
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->where('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_whatsapp', 'like', '%' . $this->search . '%')
                        ->orWhere('payment_method', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(15);

         return view('livewire.order-table', compact('orders'));
    }
}