<?php

namespace App\Http\Livewire;

use App\Models\Cart; //import model cart untuk update data
use App\Models\Post;
use Livewire\Component;

class Counter extends Component
{
    public $count = 1; //menyimpan angka counter
    public $postId; // id dari menu(post)
    public $post;
   
    public function mount($postId = null)
    {
        // $this->postId = $postId;

        // // Cek menu sudah ada di dalam keranjang
        // $cartItem = Cart::where('posts_id', $this->postId)->first();

        // if ($cartItem) { //kalo ada
        // $this->count = $cartItem->jumlah_menu; // Ambil jumlah yang sudah ada di keranjang
        // }
        if (!$postId) {
            throw new \Exception("Post ID tidak ditemukan di Counter");
        }
        // $this->postId = $postId;
        $this->post = Post::find($postId);
    }

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        if ($this->count > 1) {
            $this->count--;
        }
    }

    public function addToCart()
    {
        // Ambil data produk dari ID
        $product = \App\Models\Post::find($this->postId);
        if (!$product) return;

        // Simpan ke keranjang (kalau sudah ada, update jumlahnya)
        $cartItem = Cart::where('posts_id', $this->postId)->first();

        // Hitung total harga
        $total_menu = $this->count * $product->harga; 

        if ($cartItem) {
            
            $newQuantity = $this->count; // Langsung update dengan jumlah baru, bukan tambah lagi
            $cartItem->update([
                'jumlah_menu' => $newQuantity,
                'total_menu' => $newQuantity * $product->harga,
            ]);
        } else {
            //kalo ga ada, tambahin jadi item/menu baru
             Cart::create([
                 'pesenan_id' => '8',
                 'posts_id' => $product->id,
                 'jumlah_menu' => $this->count,
                 'total_menu' => $total_menu,
                 'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Ambil jumlah terbaru dari database setelah update
        $this->count = Cart::where('posts_id', $this->postId)->value('jumlah_menu');


        $this->dispatch('cartUpdated');

        // // Beri notifikasi sukses
        $this->dispatch('alert', type: 'success', message: "{$product->title} berhasil ditambahkan ke keranjang!");
        // session()->flash('message', "{$product->title} berhasil ditambahkan ke keranjang!");
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
