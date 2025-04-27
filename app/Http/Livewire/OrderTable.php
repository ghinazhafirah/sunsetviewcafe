<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.order-table', [
            'orders' => Order::latest()->paginate(10) // Ambil data terbaru
        ]);
    }
}