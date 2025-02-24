<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 1; //menyimpan angka counter

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

    public function render()
    {
        return view('livewire.counter');
    }
}
