<?php

namespace App\Livewire;

use Livewire\Component;

// The component is now much simpler!
class CartIconBadge extends Component
{
    public int $itemCount = 0; // The only property that matters
    public ?string $tableNumber;
    public ?string $selectedCategory;
    public ?string $search;

    // No listeners or database queries are needed here anymore.
    // JavaScript will handle everything.

    public function render()
    {
        return view('livewire.cart-icon-badge');
    }
}
