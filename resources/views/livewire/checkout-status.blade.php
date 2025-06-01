<div wire:poll.1s>
    @if (!$showReceipt)
        <div class="card mt-4 shadow-lg">
            <div class="card-body">
                {{-- <h4 class="font-weight-bold">Detail Pesanan</h4>
                <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status:</strong>
                    @if ($order->status == 'pending')
                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                    @else
                        <span class="badge bg-success">Sudah Dibayar</span>
                    @endif
                </p> --}}
                <h4 class="font-weight-bold">Detail Pesanan</h4>
                <div style="width: fit-content; margin-left: auto; margin-right: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 115px; text-align: left;">Nama</td>
                            <td style="width: 15px; text-align: center;">:</td>
                            <td style="text-align: left;"><strong>{{ $order->customer_name }}</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 135px; text-align: left;">Total Pembayaran</td>
                            <td style="width: 15px; text-align: center;">:</td>
                            <td style="text-align: left;"><strong>Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 155px; text-align: left;">Metode Pembayaran</td>
                            <td style="text-align: center;">:</td>
                            <td style="text-align: left;"><strong>{{ ucfirst($order->payment_method) }}</strong></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Status</td>
                            <td style="text-align: center;">:</td>
                            <td style="text-align: left;">
                                @if ($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                @else
                                    <span class="badge bg-success">Sudah Dibayar</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- <p class="text-start mb-1"><strong>Informasi Pemesanan</strong></p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 115px;">Nama</td>
                        <td style="width: 10px; text-align: center;">:</td>
                        <td><strong>{{ $order->customer_name }}</strong></td>
                    </tr>
                    <tr>
                        <td>No. WhatsApp</td>
                        <td style="text-align: center;">:</td>
                        <td><strong>{{ $order->customer_whatsapp }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nomor Meja</td>
                        <td style="text-align: center;">:</td>
                        <td><strong>{{ $order->table_number }}</strong></td>
                    </tr>
                </table> --}}
            </div>
        </div>

        @if ($order->status == 'pending')
            <p class="mt-3 text-muted">⚠️ Silakan lakukan pembayaran di kasir.</p>
        @else
            <p class="mt-3 text-success">
                ✅ Pembayaran telah dikonfirmasi! Klik dibawah ini untuk melihat Struk
            </p>
            <div class="mt-4">
                <a href="{{ route('receipt.show', ['uuid' => $order->uuid]) }}" class="btn btn-success">📩 STRUK
                    ANDA</a>
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
