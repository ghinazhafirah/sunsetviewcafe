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
        $this->posts = Post::all(); //semua menu tampil saat pertama kali load
    
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
        $this->selectedCategory = $categoryId; //simpen category yang dipilih
        $this->posts = $categoryId ? Post::where('category_id', $categoryId)->get() : Post::all(); //ambil menu sesuai category, kalo ga milih category, semua menu tampil
    }

    public function render()
    {
        return view('livewire.category-filter', [
            'categories' => Category::all(), //ngirim category ke view 
            'tableNumber' => $this->tableNumber // Kirim ke view
        ]);
    }

}
