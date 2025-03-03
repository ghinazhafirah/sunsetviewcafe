<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class Counter extends Component
{
    public $count = 1; //menyimpan angka counter
    public $postId;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->count = Session::get("count_{$this->postId}", 1); // Ambil dari session kalau ada
    }

    public function increment()
    {
        $this->count++;
        Session::put("count_{$this->postId}", $this->count); // Simpan di session
    }

    public function decrement()
    {
        if ($this->count > 1) {
            $this->count--;
            Session::put("count_{$this->postId}", $this->count); // Simpan di session
        }
    }

    public function addToCart()
    {
        // Ambil data produk dari ID
        $product = \App\Models\Post::find($this->postId);
        // Simpan ke sesi atau database (disesuaikan dengan implementasi)
        // $cart = session()->all();
        $total_menu = $this->count * $product->harga;

        Cart::create([
            'pesenan_id' => '8',
            'posts_id' => $product->id,
            'jumlah_menu' => $this->count,
            'total_menu' => $total_menu,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // $tes = Session::put('cart', $cart);
        // dd($tes);
        

        // // Beri notifikasi sukses
        $this->dispatch('alert', type: 'success', message: "{$product->title} berhasil ditambahkan ke keranjang!");
        // session()->flash('message', "{$product->title} berhasil ditambahkan ke keranjang!");
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
