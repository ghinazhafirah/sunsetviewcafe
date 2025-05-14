<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartDelete extends Component
{
    public $tableNumber;
    public $cart = [];

    protected $listeners = ['itemDeleted' => 'loadCart'];


    public function mount($tableNumber)
    {
        $this->tableNumber = $tableNumber;
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Cart::with('post')
            ->where('table_number', $this->tableNumber)
            ->get();
    }

    public function deleteItem($id)
    {
        Cart::where('id', $id)->delete();
        $this->loadCart();
        $this->dispatch('refreshCart')->to('cart-summary');
    }

    public function render()
    {
        return view('livewire.cart-delete', ['cart' => $this->cart]);
    }
}