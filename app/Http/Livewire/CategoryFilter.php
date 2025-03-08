<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;

class CategoryFilter extends Component
{
    public $selectedCategory; //nyimpen kategory yang dipilih
    public $posts; //nyimpen daftar menu yang ditampilkan

    public function mount()
    {
        $this->selectedCategory = null; //default, tidak ada kategori yang dipilih
        $this->posts = Post::all(); //semua menu tampil saat pertama kali load
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId; //simpen category yang dipilih
        $this->posts = $categoryId ? Post::where('category_id', $categoryId)->get() : Post::all(); //ambil menu sesuai category, kalo ga milih category, semua menu tampil
    }

    public function render()
    {
        return view('livewire.category-filter', [
            'categories' => Category::all() //ngirim category ke view 
        ]);
    }

}
