<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Session; // Tambahkan ini

class CartSummary extends Component
{
    public $orderId;
    public $tableNumber;
    public $total = 0;

    protected $listeners = ['cartUpdated' => 'updateTotal'];

   public function mount()
    {
        $this->orderId = session('order_id');

        // Ambil table_number dari Order
        if ($this->orderId) {
            $order = Order::where('order_id', $this->orderId)->first();
            $this->tableNumber = $order?->table_number; // Gunakan null-safe operator
        }

        $this->updateTotal();
    }

    public function updateTotal()
    {
         $this->orderId = Session::get('order_id'); // Pastikan selalu ambil yang terbaru dari session
        if ($this->orderId) {
            $this->total = Cart::where('order_id', $this->orderId)->sum('total_menu');
        } else {
            $this->total = 0;
        }
    }

    public function render()
    {
        return view('livewire.cart-summary');
    }
}

