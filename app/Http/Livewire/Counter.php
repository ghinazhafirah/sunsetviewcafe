<?php

namespace App\Http\Livewire;

use App\Models\Cart; //import model cart untuk update data
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class Counter extends Component
{
    public $count = 1; //menyimpan angka counter
    public $postId; // id dari menu(post)
    public $post;
    public $tableNumber; // Nomor meja
    public $note = '';
    public $orderId;
   
    public function mount($postId, $tableNumber = null, $note = '')
    {
        
        if (!$postId) {
            throw new \Exception("Post ID tidak ditemukan di Counter");
        }

        $this->postId = $postId;
        $this->tableNumber = $tableNumber ?? session('tableNumber'); // Ambil dari session jika tidak ada
        $this->note = $note;  // Simpan note dari post.blade.php

        $this->post = Post::find($postId);
        if (!$this->post) {
            throw new \Exception("Menu dengan ID {$postId} tidak ditemukan.");
        }

        $this->orderId = session('order_id');

        // Cek apakah menu sudah ada di dalam keranjang
        $cartItem = Cart::where('posts_id', $this->postId)
                        ->where('order_id', $this->orderId)
                        ->first();

        if ($cartItem) {
            $this->count = $cartItem->quantity; // Gunakan quantity yang sudah ada di cart
            $this->note = $cartItem->note ?? ''; // Simpan note di array berdasarkan ID
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
        // dd(session()->all());

        // Debugging untuk cek isi catatan
        \Log::info('Note yang diterima:', ['note' => $this->note]);

        // Ambil data produk dari ID
        $product = \App\Models\Post::find($this->postId);
        if (!$product) return;

        // Pastikan nomor meja tersedia
        if (!$this->tableNumber) {
            session()->flash('error', 'Nomor meja tidak ditemukan.');
            return;
        }

        //cek apakah di nomer meja tersebut sudah ada yang order sebelumnya atau belum
        $token = session('_token');
        
        $cekToken = Cart::where('token', $token)->get()->count();

        if ($cekToken == 0) {
            $cekCountOrder = Cart::where('table_number', $this->tableNumber)
            ->groupBy('token')
            ->select('token')
            ->get()->count();
            $this->orderId = 'ORD' . $this->tableNumber . ($cekCountOrder + 1);
            session(['order_id' => $this->orderId]);
        }
        
        // Simpan ke keranjang (kalau sudah ada, update quantitynya)
        $cartItem = Cart::where('posts_id', $this->postId)
                        ->where('order_id', $this->orderId)
                        ->first();

        if ($cartItem) {
            $totalMenu = $this->count * $product->price; 
            $cartItem->update([
            'quantity' => $this->count,
            'total_menu' => $totalMenu,
            'note' => $this->note ?? '', // Jika note sebelumnya kosong, tambahkan note baru
            ]);
        } else {
             // Hitung total harga
            $totalMenu = $this->count * $product->price; 
            //kalo ga ada, tambahin jadi item/menu baru
             Cart::create([
                 'token' => $token,
                 'order_id' => $this->orderId,
                 'posts_id' => $product->id,
                 'quantity' => $this->count,
                 'total_menu' => $totalMenu,
                 'table_number' => $this->tableNumber,
                 'note' => $this->note ?? '', // Simpan catatan ke database
                 'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Ambil quantity terbaru dari database setelah update
        $this->count = Cart::where('posts_id', $this->postId) 
                            ->where('order_id', $this->orderId)
                            ->value('quantity');

        $this->dispatch('cartUpdated');
        // // Beri notifikasi sukses
        $this->dispatch('alert', type: 'success', message: "{$product->title} berhasil ditambahkan ke keranjang!");
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
