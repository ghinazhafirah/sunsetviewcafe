    <tr>
        <td>
            <strong>{{ $title ?? 'Menu Tidak Ditemukan' }}</strong>
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
