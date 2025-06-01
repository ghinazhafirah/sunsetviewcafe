<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;

// class CategoryFilter extends Component
// {
//     public $selectedCategory; //nyimpen kategory yang dipilih
//     public $posts; //nyimpen daftar menu yang ditampilkan
//     public $tableNumber; // nyimpen no meja

//     public function mount($tableNumber = null)
//     {
//         $this->selectedCategory = null; //default, tidak ada kategori yang dipilih
//         $this->posts = Post::where('status', 'available')->get();

//         $this->selectedCategory = session('selectedCategory');

//         // Ambil nomor meja dari parameter atau session
//         if ($tableNumber) {
//             $this->tableNumber = $tableNumber;
//             session(['tableNumber' => $this->tableNumber]); // Simpan ke session
//         } else {
//             $this->tableNumber = session('tableNumber', null); // Ambil dari session jika ada
//         }

//         \Log::info('Nomor meja yang diambil:', ['tableNumber' => $this->tableNumber]);
//     }

//     public function filterByCategory($categoryId)
//     {
//         $this->selectedCategory = $categoryId;
//         session()->put('selectedCategory', $categoryId); // simpan ke session
        
//         $this->posts = $categoryId
//         ? Post::where('category_id', $categoryId)->where('status', 'available')->get()
//         : Post::where('status', 'available')->get();
//     }

//     public function render()
//     {
//         $query = Post::with('category')
//         ->where('status', 'available') // ⬅️ Ini penting!
//         ->latest();

//         if ($this->selectedCategory) {
//             $query->where('category_id', $this->selectedCategory);
//         }

//         return view('livewire.category-filter', [
//             'categories' => Category::all(), //ngirim category ke view 
//             'posts' => $query->get(), 
//             'tableNumber' => $this->tableNumber // Kirim ke view
//         ]);
//     }

// }


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

    public function mount($tableNumber = null)
    {
        // Debugging: Cek nilai selectedCategory yang datang dari Livewire/URL
    \Log::info('Mount Awal - $this->selectedCategory:', ['value' => $this->selectedCategory]);

    // Ambil nomor meja dari parameter atau session
    if ($tableNumber) {
        $this->tableNumber = $tableNumber;
        session(['tableNumber' => $this->tableNumber]); // Simpan ke session
    } else {
        $this->tableNumber = session('tableNumber', null); // Ambil dari session jika tersedia
    }

    \Log::info('Nomor meja yang diambil:', ['tableNumber' => $this->tableNumber]);
    \Log::info('Mount Akhir - $this->selectedCategory:', ['value' => $this->selectedCategory]);

    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
          \Log::info('filterByCategory called - new selectedCategory:', ['value' => $this->selectedCategory]);
        $this->search = ''; // Bersihkan pencarian saat filter kategori diterapkan
    }

    // Metode baru untuk mengaktifkan/menonaktifkan input pencarian
    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
        // Jika pencarian disembunyikan, bersihkan istilah pencarian
        if (!$this->showSearch) {
            $this->search = '';
        }
    }

    public function render()
    {
        
        // Debugging log untuk melihat nilai search
        \Log::info('Search term (render): ' . $this->search);
        \Log::info('Render - selectedCategory:', ['value' => $this->selectedCategory]);
        \Log::info('Render - search term:', ['value' => $this->search]);

        $query = Post::with('category')
            ->where('status', 'available')
            ->latest();

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        // Perubahan utama: Gunakan ->get() alih-alih ->paginate()
        $posts = $query->get();

        return view('livewire.category-filter', [
            'categories' => Category::all(),
            'posts' => $posts, // $posts sekarang adalah Collection, bukan Paginator
            'tableNumber' => $this->tableNumber,
        ]);
    }
}
