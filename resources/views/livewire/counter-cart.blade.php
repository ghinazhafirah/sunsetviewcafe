    {{-- <div class="d-flex align-items-center justify-content-center">
        <button wire:click="decrement" class="btn btn-light border-0 px-1">-</button>
        <input type="text" wire:model="count" class="form-control text-center mx-1" style="width: 50px;" readonly>
        <button wire:click="increaseCount" class="btn btn-light border-0 px-1">+</button>
    </div> --}}

    {{-- <tr>
        <td>
            <strong>{{ \App\Models\Cart::find($cartId)->post->title ?? 'Menu Tidak Ditemukan' }}</strong>
            @if (!empty($note))
                <textarea class="form-control border-warning mt-2" readonly
                    style="height: auto; overflow-y: hidden; padding: 1; line-height: 1;">{{ trim($note) }}</textarea>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center">
                <button wire:click="decrement" class="btn btn-light border-0 px-1">-</button>
                <input type="text" wire:model="count" class="form-control text-center mx-1" style="width: 50px;"
                    readonly>
                <button wire:click="increaseCount" class="btn btn-light border-0 px-1">+</button>
            </div>
        </td>
        <td class="text-end">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
    </tr> --}}

    {{-- <tr>
        <td>
            <strong>{{ \App\Models\Cart::find($cartId)->post->title ?? 'Menu Tidak Ditemukan' }}</strong>
            @if (!empty($note))
                <textarea class="form-control border-warning mt-2" readonly
                    style="height: auto; overflow-y: hidden; padding: 1; line-height: 1;">{{ trim($note) }}</textarea>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center">
                <button wire:click="decrement" class="btn btn-light border-0 px-1">-</button>
                <input type="text" wire:model="count" class="form-control text-center mx-1" style="width: 50px;"
                    readonly>
                <button wire:click="increaseCount" class="btn btn-light border-0 px-1">+</button>
            </div>
        </td>
        <td class="text-end">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
    </tr>

    @if ($showSummary)
        <tr>
            <td colspan="3">
                <div class="cart-summary pt-4 border-top">
                    <h5>Cart Total</h5>
                    <div class="row d-flex align-items-center">
                        <div class="col-md-6 col-4">
                            <span>Total:</span>
                        </div>
                        <div class="col-md-6 col-8 text-end">
                            <span>Rp {{ number_format($totalAll, 0, ',', '.') }}</span>
                        </div>
                        <div class="cart-btn mt-3">
                            <a href="{{ route('checkout.index', ['table' => $tableNumber]) }}"
                                class="btn amado-btn w-100">Next</a>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endif --}}

    <tr>
        <td>
            <strong>{{ \App\Models\Cart::find($cartId)->post->title ?? 'Menu Tidak Ditemukan' }}</strong>
            @if (!empty($note))
                <textarea class="form-control border-warning mt-2" readonly style="height: auto; overflow-y: hidden; line-height: 1;">{{ trim($note) }}</textarea>
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center">
                <button wire:click="decrement" class="btn btn-light border-0 px-1">-</button>
                <input type="text" wire:model="count" class="form-control text-center mx-1" style="width: 50px;"
                    readonly>
                <button wire:click="increaseCount" class="btn btn-light border-0 px-1">+</button>
            </div>
        </td>
        <td class="text-end">
            Rp {{ number_format($totalHarga, 0, ',', '.') }}
        </td>
    </tr>
