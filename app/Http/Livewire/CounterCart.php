<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CounterCart extends Component
{
    public $count = 1; //simpen quantity menu/item di cart
    public $cartId; //simpen ID item di cart
    public $totalHarga; //simpen total harga berdasarkan quantity menu/item yg dipilih
    public $notes; //simpen note
    public $title;

    public $showSummary = false; // default false
    public $tableNumber;
    public $totalAll = 0;

    protected $listeners = [
        'removeFromCart' => 'removeFromCart',
        'refreshSelf' => '$refresh',
    ];

    public function mount($cartId, $quantity, $totalMenu, $showSummary = false, $tableNumber = null)
    {
        $this->cartId      = $cartId;
        $this->count       = $quantity;
        $this->totalHarga  = $totalMenu;
        $this->showSummary = $showSummary;
        $this->tableNumber = $tableNumber;

        // Ambil note dan hitung ulang totalHarga
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $this->note       = $cartItem->note;
             $this->title      = $cartItem->post->title ?? 'Menu Tidak Ditemukan';
            $this->totalHarga = $cartItem->quantity * $cartItem->post->price;
        }

        // Jika ini komponen terakhir, hitung total keseluruhan
        if ($this->showSummary) {
            $this->totalAll = Cart::sum('total_menu');
        }
    }

    public function increaseCount()
    {
        $this->count++;
        $this->updateCart();
    }

    public function addToCart($postId)
    {
        \Log::info('Livewire addToCart dipanggil', ['postId' => $postId, 'note' => $this->note]);
        $this->dispatch('addToCart', $postId, $this->count, $this->note);   
    }


    public function decrement()
    {
        if ($this->count > 1) {
            $this->count--;
            $this->updateCart();
       } else {
        $this->dispatch('confirmDelete', cartId: $this->cartId);
        }
    }

    public function updateCart()
    {
        $cartItem = Cart::find($this->cartId);
        if ($cartItem) {
            //hitung ulang total harga
            $this->totalHarga = $this->count * $cartItem->post->price; //total harga berubah saat quantity menu berubah

            //update ke database
            $cartItem->update([
                'quantity' => $this->count,
                'total_menu' => $this->totalHarga,
            ]);
            
             $this->totalAll = Cart::sum('total_menu');
            // Emit event agar livewire bisa tau perubahan dan tampilan
            $this->dispatch('cartUpdated');
        }
    }

    public function removeFromCart($cartId)
    {
        \Log::info('Menghapus item dari cart:', ['cartId' => $cartId]);

        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->delete();
            $this->dispatch('cartUpdated');
            $this->dispatch('itemDeleted');
        }
    }

    public function render()
    {
        $cartItems = Cart::all();

        return view('livewire.counter-cart', [
            'totalAll' => $this->totalAll,
            'showSummary' => $this->showSummary,
            'tableNumber' => $this->tableNumber,
        ]);
    }
}