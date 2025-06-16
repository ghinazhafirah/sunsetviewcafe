<div class="cart-summary pt-3">

    <div class="row align-items-center">
        <div class="col-6"><span>
                <h5>Total</h5>
            </span></div>
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
