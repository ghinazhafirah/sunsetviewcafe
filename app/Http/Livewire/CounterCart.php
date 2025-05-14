<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Post;

class CounterCart extends Component
{
    public $count = 1; //simpen quantity menu/item di cart
    public $cartId; //simpen ID item di cart
    public $totalHarga; //simpen total harga berdasarkan quantity menu/item yg dipilih
    public $notes; //simpen note

    public $showSummary = false; // default false
    public $tableNumber;
    public $totalAll = 0;

    // public function mount($cartId, $quantity, $totalMenu) //supaya bisa dipake di blade
    // {
    //     $this->cartId = $cartId;
    //     $this->count = $quantity;
    //     $this->totalHarga = $totalMenu;
            
    //     //ambil menu/item di cart berdasarkan id
    //     $cartItem = Cart::find($cartId); //mencari menu dalam cart berdasarkan cart id
    //     if ($cartItem) {
    //         $this->totalHarga = $cartItem->quantity * $cartItem->post->price;
    //         $this->note = $cartItem->note; // Tampilkan catatan tanpa bisa diubah
    //     }
    //     \Log::info("Mounting CounterCart", [
    //         'cart_id' => $this->cartId,
    //         'note' => $this->note, // Cek apakah note masuk
    //     ]);
    // }
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
            $this->removeFromCart(); // fungsi hapus, jika quantity=0
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
            
            // Emit event agar livewire bisa tau perubahan dan tampilan
            $this->dispatch('cartUpdated');
        }
    }

    public function removeFromCart()
    {
        $cartItem = Cart::find($this->cartId);

        if ($cartItem) {
            $cartItem->delete(); // Hapus item dari database
            $this->dispatch('cartUpdated'); // Emit event agar tampilan Livewire diperbarui
            $this->dispatch('itemRemoved', $this->cartId); // Kirim event ke parent
        }
    }

    public function render()
    {
        // $cartItems = [];
        // $totalAll = 0;

        // if ($this->showSummary) {
        //     $cartItems = Cart::all();
        //     $totalAll = $cartItems->sum('total_menu');
        // }

        return view('livewire.counter-cart', [
            'totalAll' => $this->totalAll,
            'showSummary' => $this->showSummary,
            'tableNumber' => $this->tableNumber
        ]);
        // return view('livewire.counter-cart');
    }
}