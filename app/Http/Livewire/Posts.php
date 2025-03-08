<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class Posts extends Component
{
    public $selectedCategory = null;

    protected $listeners = ['categorySelected' => 'updateCategory'];

    public function updateCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function render()
    {
        $posts = Post::when($this->selectedCategory, function ($query) {
            return $query->where('category_id', $this->selectedCategory);
        })->get();

        return view('livewire.posts', compact('posts'));
    }
}
