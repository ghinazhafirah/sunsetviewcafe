<div class="cart-summary pt-3">
    <h5>Cart Total</h5>
    <div class="row align-items-center">
        <div class="col-6"><span>Total:</span></div>
        <div class="col-6 text-end"><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
        <div class="col-12 mt-3">
            {{-- <a href="{{ route('checkout.index', ['table' => $tableNumber]) }}" class="btn amado-btn w-100">Next</a> --}}
            <a href="{{ route('checkout.index', ['table' => $tableNumber]) }}"
                class="btn amado-btn w-100 btn-warning {{ $total <= 0 ? 'disabled' : 'btn-hover-dark' }}"
                {{ $total <= 0 ? 'aria-disabled="true"' : '' }}>
                Next
            </a> 
        </div>
    </div>
</div>
