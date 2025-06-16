<div class="cart-table clearfix">
    @if ($cartItems->isEmpty())
        <p class="text-center">Keranjang Anda kosong!</p>
    @else
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</strong>
                            @if (!empty($item->note))
                                <textarea class="form-control border-warning mt-2" readonly style="height: auto; overflow-y: hidden; line-height: 1;">{{ trim($item->note) }}</textarea>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                {{-- Gunakan wire:click dengan meneruskan ID item --}}
                                <button wire:click="decreaseQuantity({{ $item->id }})"
                                    class="btn btn-light border-0 px-1">-</button>
                                <input type="text" value="{{ $item->quantity }}"
                                    class="form-control text-center mx-1" style="width: 50px;" readonly>
                                <button wire:click="increaseQuantity({{ $item->id }})"
                                    class="btn btn-light border-0 px-1">+</button>
                            </div>
                        </td>

                        <td class="text-end">
                            Rp {{ number_format($item->total_menu, 0, ',', '.') }}
                            {{-- Tombol hapus langsung di sini, akan memicu event konfirmasi --}}
                            {{-- <button wire:click="dispatch('confirmDelete', { cartId: {{ $item->id }} })" class="btn btn-danger btn-sm mt-1">Hapus</button> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('confirmDelete', function(payload) {
            const cartId = payload.cartId;

            Swal.fire({
                title: 'Hapus Item?',
                text: "Apakah Anda yakin ingin menghapusnya?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('removeFromCart', {
                        cartId: cartId
                    });
                }
            });
        });
    });
</script> --}}
