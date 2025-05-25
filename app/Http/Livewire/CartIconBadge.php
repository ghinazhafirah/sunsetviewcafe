<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartIconBadge extends Component
{
    public $itemCount = 0;
    public $tableNumber;

    protected $listeners = ['cartUpdated' => 'getItemCount'];

    public function mount($tableNumber = null)
    {
        $this->tableNumber = $tableNumber;
        $this->getItemCount();
    }

    public function getItemCount()
    {
        $this->itemCount = Cart::where('table_number', $this->tableNumber)->sum('quantity');
    }
    
    public function render()
    {
        return view('livewire.cart-icon-badge');
    }
}
