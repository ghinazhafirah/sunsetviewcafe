<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderStatus extends Component
{
    public $order;
    protected $listeners = ['paymentUpdated' => 'refreshStatus'];

    public function mount($orderId)
    {
        $this->order = Order::find($orderId);
    }

    public function refreshStatus()
    {
        $this->order = Order::find($this->order->id);
    }
    
    public function render()
    {
        return view('livewire.order-status');
    }
}