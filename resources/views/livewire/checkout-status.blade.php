<div wire:poll.1s>
    @if (!$showReceipt)
        <div class="card mt-4 shadow-lg">
            <div class="card-body">
                <h4 class="font-weight-bold">Detail Pesanan</h4>
                <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status:</strong>
                    @if ($order->status == 'pending')
                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                    @else
                        <span class="badge bg-success">Sudah Dibayar</span>
                    @endif
                </p>
            </div>
        </div>

        @if ($order->status == 'pending')
            <p class="mt-3 text-muted">⚠️ Silakan lakukan pembayaran di kasir.</p>
        @else
            <p class="mt-3 text-success">
                ✅ Pembayaran telah dikonfirmasi! Klik dibawah ini untuk melihat Struk
            </p>
            <div class="mt-4">
                <a href="{{ route('receipt.show', ['uuid' => $order->uuid]) }}" class="btn btn-success">📩 STRUK ANDA</a>
            </div>
        @endif
    @endif
</div>

<script>
    function printReceipt() {
        var printContents = document.querySelector(".border.rounded.shadow").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload halaman setelah cetak
    }
</script>
