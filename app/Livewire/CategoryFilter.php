<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Log; // Gunakan Facade Log

class CategoryFilter extends Component
{

    public $selectedCategory; // Menyimpan kategori yang dipilih
    public $tableNumber;      // Menyimpan nomor meja
    public $search = '';      // Menyimpan istilah pencarian
    public $showSearch = false; // Mengontrol visibilitas input pencarian

    // Properti query string agar nilai search dan category tetap ada di URL saat refresh
    protected $queryString = [
        'selectedCategory' => ['except' => null],
        'search' => ['except' => ''],
       
    ];

    //   // Tambahkan listener untuk event dari browser (JavaScript)
    // protected $listeners = [
    //     'cartDataFromBrowser' => 'handleCartDataFromBrowser'
    // ];

    public function mount($tableNumber = null)
    {
    //     // Debugging: Cek nilai selectedCategory yang datang dari Livewire/URL
    // \Log::info('Mount Awal - $this->selectedCategory:', ['value' => $this->selectedCategory]);

    // // Ambil nomor meja dari parameter atau session
    // if ($tableNumber) {
    //     $this->tableNumber = $tableNumber;
    //     session(['tableNumber' => $this->tableNumber]); // Simpan ke session
    // } else {
    //     $this->tableNumber = session('tableNumber', null); // Ambil dari session jika tersedia
    // }

    // \Log::info('Nomor meja yang diambil:', ['tableNumber' => $this->tableNumber]);
    // \Log::info('Mount Akhir - $this->selectedCategory:', ['value' => $this->selectedCategory]);
    // // Pemicu event saat komponen pertama kali dimuat atau di-refresh
    // $this->dispatch('requestCartData');
    //     Log::info('[CategoryFilter PHP] Mounted, dispatched requestCartData.');
        $this->tableNumber = $tableNumber;
    }

    // // Metode untuk menerima data keranjang dari JavaScript
    // public function handleCartDataFromBrowser($cartDataJson, $tableNumberFromJs)
    // {
    //     // Simpan data keranjang di session agar Livewire Component lain (CartIconBadge) bisa mengaksesnya
    //     // Gunakan tableNumberFromJs karena ini adalah data yang valid dari client
    //     $sessionKey = 'cartDataJson_' . ($tableNumberFromJs ?? 'default');
    //     session([$sessionKey => $cartDataJson]);

    //     // Setelah menerima dan menyimpan data, panggil komponen CartIconBadge untuk memperbarui dirinya
    //     $this->dispatch('refreshCartBadge'); // Event ini akan ditangkap oleh CartIconBadge
    //     Log::info('[CategoryFilter PHP] Received cart data from browser, stored in session: ' . $sessionKey . ', dispatched refreshCartBadge.');
    // }

    //   // Dipanggil setiap kali properti 'selectedCategory' berubah
    // public function updatedSelectedCategory()
    // {
    //     $this->search = ''; // Bersihkan pencarian saat filter kategori diterapkan
    //     $this->dispatch('updateCartBadges'); // Pemicu event kustom
    //     // Jika Anda menggunakan paginasi, tambahkan $this->resetPage();
    // }

    //  // Dipanggil setiap kali properti 'search' berubah
    // public function updatedSearch()
    // {
    //     $this->dispatch('updateCartBadges'); // Pemicu event kustom
    //     // Jika Anda menggunakan paginasi, tambahkan $this->resetPage();
    // }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
          \Log::info('filterByCategory called - new selectedCategory:', ['value' => $this->selectedCategory]);
        $this->search = ''; // Bersihkan pencarian saat filter kategori diterapkan
        //   $this->dispatch('requestCartData');
    }

     public function performSearch()
    {
        // Livewire secara otomatis merender ulang pada perubahan properti publik
        // Untuk wire:keydown.enter, metode ini memastikan pencarian "difinalisasi" jika debounce digunakan
    }

    // Metode baru untuk mengaktifkan/menonaktifkan input pencarian
    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
        // Jika pencarian disembunyikan, bersihkan istilah pencarian
        if (!$this->showSearch) {
            $this->search = '';
        }
        // Minta JS untuk mengirim data keranjang lagi
        // $this->dispatch('requestCartData');
    }

    public function render()
    {
        
        // Debugging log untuk melihat nilai search
        // \Log::info('Search term (render): ' . $this->search);
        // \Log::info('Render - selectedCategory:', ['value' => $this->selectedCategory]);
        // \Log::info('Render - search term:', ['value' => $this->search]);

        // $query = Post::with('category')
        //     ->where('status', 'available')
        //     ->latest();

        // if ($this->selectedCategory) {
        //     $query->where('category_id', $this->selectedCategory);
        // }

        // if ($this->search) {
        //     $query->where(function ($q) {
        //         $q->where('title', 'like', '%' . $this->search . '%')
        //             ->orWhere('excerpt', 'like', '%' . $this->search . '%');
        //     });
        // }

        // // Perubahan utama: Gunakan ->get() alih-alih ->paginate()
        // $posts = $query->get();

        // return view('livewire.category-filter', [
        //     'categories' => Category::all(),
        //     'posts' => $posts, // $posts sekarang adalah Collection, bukan Paginator
        //     'tableNumber' => $this->tableNumber,
        // ]);

         $query = Post::query();

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        $posts = $query->get();
        $categories = Category::all();

        // Kirim event browser setelah komponen (dan karenanya bagian menu) telah dirender.
        // Ini akan memberi tahu JavaScript Anda untuk memperbarui badge.
        $this->dispatch('menuUpdatedFromLivewire'); // <-- Tambahkan baris ini

        return view('livewire.category-filter', [ // Ganti dengan jalur tampilan aktual Anda
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}