<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Post;

class CounterCart extends Component
{
    public $count = 1; //simpen jumlah menu/item di cart
    public $cartId; //simpen ID item di cart
    public $totalHarga; //simpen total harga berdasarkan jumlah menu/item yg dipilih

    public function mount($cartId, $jumlahMenu, $totalMenu) //supaya bisa dipake di blade
    {
        $this->cartId = $cartId;
        $this->count = $jumlahMenu;
        $this->totalHarga = $totalMenu;

        //ambil menu/item di cart berdasarkan id
        $cartItem = Cart::find($cartId); //mencari menu dalam cart berdasarkan cart id
        if ($cartItem) {
            $this->totalHarga = $cartItem->jumlah_menu * $cartItem->post->harga;
        }
    }

    public function increaseCount()
    {
        $this->count++;
        $this->updateCart();
    }
    public function addToCart($postId)
    {
        $this->dispatch('addToCart', $postId, $this->count);
    }


    public function decrement()
    {
        if ($this->count > 1) {
            $this->count--;
            $this->updateCart();
        } else {
            $this->removeFromCart(); // fungsi hapus, jika jumlah=0
        }
    }

    public function updateCart()
    {
        $cartItem = Cart::find($this->cartId);
        if ($cartItem) {
            //hitung ulang total harga
            $this->totalHarga = $this->count * $cartItem->post->harga; //total harga berubah saat jumlah menu berubah

            //update ke database
            $cartItem->update([
                'jumlah_menu' => $this->count,
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
        return view('livewire.counter-cart');
    }
}