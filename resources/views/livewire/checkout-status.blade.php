<div wire:poll.1s>
    @if (!$showReceipt)
        <div class="card mt-4 shadow-lg">
            <div class="card-header text-center bg-warning">
                <h4 class="font-weight-bold mb-0 text-dark">Detail Pesanan</h4>
            </div>
            <div class="card-body pb-0">
                <div class="align-items-center justify-content-center">
                    <div class="container p-0">
                        <div class="row justify-content-center">
                            <div class="col-7 col-sm-6 col-md-3 p-2 me-2 text-start">Nama</div>
                            {{-- <div class="col-1 py-2">:</div> --}}
                            <div class="col-4 py-2 px-0 text-start"><strong>: {{ $order->customer_name }}</strong></div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-7 col-sm-6 col-md-3 p-2 me-2 text-start">Total Pembayaran</div>
                            {{-- <div class="col-1 py-2">:</div> --}}
                            <div class="col-4 py-2 px-0 text-start"><strong>: Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</strong></div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-7 col-sm-6 col-md-3 p-2 me-2 text-start">Metode Pembayaran</div>
                            {{-- <div class="col-1 py-2">:</div> --}}
                            <div class="col-4 py-2 px-0 text-start">
                                <strong>: {{ ucfirst($order->payment_method) }}</strong>
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-center mt-4">
                            @if ($order->status == 'pending')
                                <span class="badge bg-warning text-dark me-2 fs-6">Menunggu Pembayaran</span>
                            @else
                                <span class="badge bg-success fs-6">Sudah Dibayar</span>
                            @endif
                        </div> --}}
                    </div>
                </div>
            </div>
            <hr class="border-warning">
            <div class="card-footer bg-white border-top-0 text-start p-0">
                @if ($order->status == 'pending')
                    <div class="d-flex align-items-center justify-content-center flex-wrap">
                        <i class="fas fa-exclamation-triangle text-warning me-2 mb-4" style="font-size: 1.2em;"></i>
                        <p class="text-warning mb-4">Silakan lakukan pembayaran di kasir.</p>
                    </div>
                @endif
            </div>
            @if ($order->status == 'pending')
                {{-- Ini sudah ditangani di card-footer di atas --}}
            @else
                <div class="text-center">
                    <p class="text-success mb-2">
                        ✅ Pembayaran telah dikonfirmasi! Klik di bawah ini untuk melihat Struk
                    </p>
                    <div class="mt-2 pb-3">
                        <a href="{{ route('receipt.show', ['uuid' => $order->uuid]) }}" class="btn btn-success">
                            <i class="fas fa-receipt"></i> STRUK ANDA
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    function printReceipt() {
        var printContents = document.querySelector(".border.rounded.shadow") ? document.querySelector(
            ".border.rounded.shadow").innerHTML : '';
        if (!printContents) {
            console.warn("Selektor konten cetak tidak menemukan elemen. Harap periksa selektornya.");
            return;
        }

        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
