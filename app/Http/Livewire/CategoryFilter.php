<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;

class CategoryFilter extends Component
{
    public $selectedCategory; //nyimpen kategory yang dipilih
    public $posts; //nyimpen daftar menu yang ditampilkan
    public $tableNumber; // nyimpen no meja

    public function mount($tableNumber = null)
    {
        $this->selectedCategory = null; //default, tidak ada kategori yang dipilih
        $this->posts = Post::where('status', 'available')->get();

        // Ambil nomor meja dari parameter atau session
        if ($tableNumber) {
            $this->tableNumber = $tableNumber;
            session(['tableNumber' => $this->tableNumber]); // Simpan ke session
        } else {
            $this->tableNumber = session('tableNumber', null); // Ambil dari session jika ada
        }

        \Log::info('Nomor meja yang diambil:', ['tableNumber' => $this->tableNumber]);
    }

    public function filterByCategory($categoryId)
    {
      
        $this->posts = $categoryId
        ? Post::where('category_id', $categoryId)->where('status', 'available')->get()
        : Post::where('status', 'available')->get();
    }

    public function render()
    {
        $query = Post::with('category')
        ->where('status', 'available') // ⬅️ Ini penting!
        ->latest();

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.category-filter', [
            'categories' => Category::all(), //ngirim category ke view 
            'posts' => $query->get(), 
            'tableNumber' => $this->tableNumber // Kirim ke view
        ]);
    }

}
