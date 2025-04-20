<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;

class CheckoutStatus extends Component
{
    public $order;
    public $showReceipt = false; // ✅ Tambahkan variabel ini agar bisa diakses di Blade

    protected $listeners = ['paymentUpdated' => 'refreshStatus'];

    public function mount($orderId)
    {
        $this->order = Order::find($orderId);
    }

    public function refreshStatus($id)
    {
        // dd("Event Diterima dengan ID:", $id); // Debugging

        if ($this->order->id == $id) {
            $this->order = Order::find($id);
        }
    }

    public function showReceipt()
    {
        logger('✅ Tombol STRUK ANDA diklik!');
        $this->showReceipt = true; // Ketika tombol diklik, tampilkan struk
    }

    public function render()
    {
        return view('livewire.checkout-status', [
            'order' => $this->order,
            'showReceipt' => $this->showReceipt, // ✅ Kirim ke Blade agar tidak undefined
        ]);    
    }
}
