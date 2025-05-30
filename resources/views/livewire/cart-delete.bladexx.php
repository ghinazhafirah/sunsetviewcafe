<div class="cart-table clearfix">
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Menu</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $item)
                @livewire(
                    'counter-cart',
                    [
                        'cartId' => $item->id,
                        'quantity' => $item->quantity,
                        'totalMenu' => $item->total_menu,
                        'title' => $item->post->title,
                        'note' => $item->note,
                        'showSummary' => $loop->last,
                        'tableNumber' => $tableNumber,
                    ],
                    key('cart-' . $item->id)
                )
            @endforeach
        </tbody>
    </table>
</div>

<script>
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
</script>
