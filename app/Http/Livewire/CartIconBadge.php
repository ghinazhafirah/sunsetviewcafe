<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class CartIconBadge extends Component
{
    public $itemCount = 0;
    public $tableNumber;
    public $selectedCategory; // Tambahkan properti ini
    public $search;           // Tambahkan properti ini

    protected $listeners = ['cartUpdated' => 'getItemCount'];

     public function mount($tableNumber = null, $selectedCategory = null, $search = null)
    {
        $this->tableNumber = $tableNumber;
        $this->selectedCategory = $selectedCategory; // Inisialisasi dari parameter mount
        $this->search = $search;                   // Inisialisasi dari parameter mount
        $this->getItemCount();
    }

    public function getItemCount()
    {        
         // Ambil order_id yang aktif dari session
        $orderId = Session::get('order_id');

        // Jika ada order_id di session, hitung item berdasarkan order_id tersebut
        if ($orderId) {
            $this->itemCount = Cart::where('order_id', $orderId)->sum('quantity');
        } else {
            // Jika tidak ada order_id di session (misalnya setelah clearCart),
            // maka secara default jumlah item adalah 0.
            $this->itemCount = 0;
        }
    }
    
    public function render()
    {
        return view('livewire.cart-icon-badge');
    }
}
