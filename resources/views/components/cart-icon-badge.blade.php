{{-- resources/views/components/cart-icon-badge.blade.php --}}

@props(['tableNumber', 'selectedCategory' => null, 'search' => null])

{{-- Add a unique ID to the root div so our script can find it easily --}}
<div id="cart-icon-component-wrapper">
    <a href="{{ route('cart.refactor.index', [
        'tableNumber' => $tableNumber,
    ]) }}"
        class="btn btn-light position-relative py-2">

        <i class="fas fa-shopping-cart" style="font-size: 20px"></i>

        {{-- 
            The badge element now starts empty and hidden.
            JavaScript will be solely responsible for updating its content and visibility.
        --}}
        <span id="cart-item-count-badge"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="display: none;">
        </span>
    </a>
</div>
