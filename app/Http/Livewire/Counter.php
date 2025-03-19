<?php

namespace App\Http\Livewire;

use App\Models\Cart; //import model cart untuk update data
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Counter extends Component
{
    public $count = 1; //menyimpan angka counter
    public $postId; // id dari menu(post)
    public $post;
    public $tableNumber; // Nomor meja
    public $note = []; // Variabel untuk menyimpan catatan
   
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

        // Cek apakah menu sudah ada di dalam keranjang
        $cartItem = Cart::where('posts_id', $this->postId)
                        ->where('table_number', $this->tableNumber)
                        ->first();

        if ($cartItem) {
            $this->count = $cartItem->jumlah_menu; // Gunakan jumlah yang sudah ada di cart
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
        // Debugging untuk cek isi catatan
        \Log::info('Note yang diterima:', ['note' => $this->note]);

        // dd($this->note);

        // Ambil data produk dari ID
        $product = \App\Models\Post::find($this->postId);
        if (!$product) return;

        // Pastikan nomor meja tersedia
        if (!$this->tableNumber) {
            session()->flash('error', 'Nomor meja tidak ditemukan.');
            return;
        }

        // Simpan ke keranjang (kalau sudah ada, update jumlahnya)
        $cartItem = Cart::where('posts_id', $this->postId)
                        ->where('table_number', $this->tableNumber)
                        ->first();

        if ($cartItem) {
            $totalMenu = $this->count * $product->harga; 
            $cartItem->update([
            'jumlah_menu' => $this->count,
            'total_menu' => $totalMenu,
            'note' => $this->note ?? '', // Jika note sebelumnya kosong, tambahkan note baru
            ]);
        } else {
             // Hitung total harga
            $totalMenu = $this->count * $product->harga; 
            //kalo ga ada, tambahin jadi item/menu baru
             Cart::create([
                 'pesenan_id' => '8',
                 'posts_id' => $product->id,
                 'jumlah_menu' => $this->count,
                 'total_menu' => $totalMenu,
                 'table_number' => $this->tableNumber,
                 'note' => $this->note ?? '', // Simpan catatan ke database
                 'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Ambil jumlah terbaru dari database setelah update
        $this->count = Cart::where('posts_id', $this->postId) 
                            ->where('table_number', $this->tableNumber)
                            ->value('jumlah_menu');

        // Pastikan catatan tersimpan dengan cek database
        $savedNote = Cart::where('posts_id', $this->postId)
                        ->where('table_number', $this->tableNumber)
                        ->value('note');

        \Log::info('Note yang disimpan:', ['note' => $savedNote]);


        $this->dispatch('cartUpdated');
        // // Beri notifikasi sukses
        $this->dispatch('alert', type: 'success', message: "{$product->title} berhasil ditambahkan ke keranjang!");
        // session()->flash('message', "{$product->title} berhasil ditambahkan ke keranjang!");
    }

    // public function updateNote($cartId)
    // {
    //     $cartItem = Cart::find($cartId);
    //     if (!$cartItem) {
    //         return;
    //     }

    //     $cartItem->update(['note' => $this->note]);

    //     \Log::info("Note diperbarui untuk cart ID {$cartId}", ['note' => $this->note]);

    //     $this->dispatch('cartUpdated');
    // }


    public function render()
    {
        return view('livewire.counter');
    }
}
