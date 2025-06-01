<a href="{{ route('cart.show', ['table' => $tableNumber, 'selectedCategory' => $selectedCategory ?? null,
    'search' => $search ?? null,]) }}" class="btn btn-light position-relative py-2">
    <i class="fas fa-shopping-cart" style="font-size: 20px"></i>
    @if ($itemCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $itemCount > 99 ? '99+' : $itemCount }}
        </span>
    @endif
</a>
