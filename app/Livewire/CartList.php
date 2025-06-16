<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Session; // Tambahkan ini

class CartList extends Component
{
    public $tableNumber;
    public $cartItems = [];
    public $totalAll = 0;

    // protected $listeners = ['itemDeleted' => 'loadCart'];
    protected $listeners = ['cartUpdated' => 'loadCart', 'itemDeleted' => 'loadCart',  'deleteConfirmed' => 'deleteItem']; 


    public function mount($tableNumber)
    {
        $this->tableNumber = $tableNumber;
        $this->loadCart();
    }

    public function loadCart()
    {
        // $this->cart = Cart::with('post')
        //     ->where('table_number', $this->tableNumber)
        //     ->get();

        $orderId = Session::get('order_id');

        if ($orderId) {
            $this->cartItems = Cart::with('post')
                ->where('order_id', $orderId) // Filter berdasarkan order_id aktif
                ->get();
            $this->totalAll = $this->cartItems->sum('total_menu');
        } else {
            $this->cartItems = collect(); // Keranjang kosong
            $this->totalAll = 0;
        }
    }

     public function increaseQuantity($cartId)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->total_menu = $cartItem->quantity * $cartItem->post->price;
            $cartItem->save();
            $this->loadCart(); // Reload data setelah perubahan
            $this->dispatch('cartUpdated'); // Beri tahu komponen lain (badge, summary)
        }
    }

    // Fungsi untuk mengurangi kuantitas
    public function decreaseQuantity($cartId)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity--;
                $cartItem->total_menu = $cartItem->quantity * $cartItem->post->price;
                $cartItem->save();
                $this->loadCart();
                $this->dispatch('cartUpdated');
            } else {
                // Jika kuantitas 1 dan dikurangi, konfirmasi hapus
                $this->dispatch('confirmDelete', cartId: $cartId);
                // return; // Jangan lanjutkan update jika akan dihapus
            }
            // $this->loadCart(); // Reload data setelah perubahan
            // $this->dispatch('cartUpdated'); // Beri tahu komponen lain
        }
    }

    public function deleteItem($cartId)
    {
        // Cart::where('id', $id)->delete();
        // $this->loadCart();
        // $this->dispatch('refreshCart')->to('cart-summary');
        
         $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->delete();
            $this->loadCart(); // Reload data setelah penghapusan
            $this->dispatch('cartUpdated'); // Beri tahu komponen lain
            $this->dispatch('itemDeleted'); // Tambahan event untuk debugging/spesifik jika diperlukan
        }
    }

    public function render()
    {
        // return view('livewire.cart-delete', ['cart' => $this->cart]);
        return view('livewire.cart-list', [ // Ubah nama view sesuai nama kelas
            'cartItems' => $this->cartItems,
            'totalAll' => $this->totalAll,
        ]);
    }
}