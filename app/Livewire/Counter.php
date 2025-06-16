<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;

class Counter extends Component
{
    public $count = 1;
    public $postId;
    public $post;
    public $tableNumber;
    public $note = '';
    public $orderId;

    public function mount($postId, $tableNumber = null, $note = '')
    {
        // * Log postId dan tableNumber untuk debugging
        Log::info('Mounting Counter component with postId:', ['postId' => $postId, 'tableNumber' => $tableNumber]);

        if (!$postId) {
            throw new Exception("Post ID tidak ditemukan di Counter");
        }

        $this->postId = $postId;
        $this->tableNumber = $tableNumber ?? session('tableNumber');
        $this->note = $note;

        $this->post = Post::find($postId);
        if (!$this->post) {
            throw new Exception("Menu dengan ID {$postId} tidak ditemukan.");
        }

        $this->orderId = session('order_id');

        $cartItem = Cart::where('posts_id', $this->postId)
            ->where('order_id', $this->orderId)
            ->first();

        if ($cartItem) {
            $this->count = $cartItem->quantity;
            $this->note = $cartItem->note ?? '';
        }
    }

    public function loadProductQuantityFromSessionStorage(int $quantity)
    {
        // * Log quantity untuk debugging
        Log::info('Loading product quantity from session storage:', ['quantity' => $quantity]);

        if ($quantity < 1) {
            $this->count = 1;
        } else {
            $this->count = $quantity;
        }
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
        // * 1. Cari produk berdasarkan postId
        $product = Post::find($this->postId);
        if (!$product) return;

        // * 2. Validasi apakah tableNumber sudah diisi
        if (!$this->tableNumber) {
            session()->flash('error', 'Nomor meja tidak ditemukan.');
            return;
        }

        // * 4. Dispatch event untuk menambahkan produk ke keranjang
        $this->dispatch('alert', type: 'success', message: "{$product->title} berhasil ditambahkan ke keranjang!");

        // * Dispatch event untuk mengupdate item di sessionStorage
        $this->dispatch(
            'updateCart',
            [
                'tableNumber' => $this->tableNumber,
                'product' => [
                    'id' => $product->id,
                    'quantity' => $this->count,
                    'note' => $this->note,
                ],
            ]
        );
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
