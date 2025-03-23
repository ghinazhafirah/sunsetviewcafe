@extends('layouts.main')

@section('container')
<div class="container text-center mt-5">
    <h2 class="text-success">✅ Pesanan Anda Berhasil!</h2>
    
    <div class="card mt-4 shadow-lg">
        <div class="card-body">
            <h4 class="font-weight-bold">Detail Pesanan</h4>
            <p><strong>Nama:</strong> {{ $transaction->customer_name }}</p>
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaction->payment_method) }}</p>
            <p><strong>Status:</strong> 
                @if ($transaction->status == 'pending')
                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                @else
                    <span class="badge bg-success">Sudah Dibayar</span>
                @endif
            </p>
        </div>
    </div>

    @if ($transaction->status == 'pending')
        <p class="mt-3 text-muted">⚠️ Silakan lakukan pembayaran di kasir.</p>
    @else
        <p class="mt-3 text-success">
            ✅ Pembayaran telah dikonfirmasi! Struk akan dikirimkan melalui WhatsApp ke:  
            <strong>{{ $transaction->customer_whatsapp }}</strong>
        </p>
    @endif

    <div class="mt-4">
        @if ($transaction->status == 'paid')
            <a id="whatsappLink" class="btn btn-success">📩 Kirim Struk ke WhatsApp</a>
        @endif
    </div>
</div>

<script>
    document.getElementById('whatsappLink')?.addEventListener('click', function() {
        var phone = "{{ str_replace('+', '', $transaction->customer_whatsapp) }}"; // Nomor pelanggan dari DB
        var message = `Halo {{ $transaction->customer_name }},\n\n` +
                      `Pesanan Anda telah berhasil!\n` +
                      `📝 Nomor Transaksi: {{ $transaction->order_id }}\n` +
                      `💰 Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}\n` +
                      `📅 Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}\n\n` +
                      `Terima kasih telah berkunjung ke Sunset View Cafe! ☕🍽️`;

        var encodedMessage = encodeURIComponent(message); // Encode pesan agar aman di URL
        window.location.href = "https://wa.me/" + phone + "?text=" + encodedMessage;
    });
</script>

@endsection

{{-- 
    <div class="mt-4">
        <a id="whatsappLink" class="btn btn-success">📩 Hubungi Kasir</a>
    </div>
    
    <script>
        document.getElementById('whatsappLink').addEventListener('click', function() {
            var phone = "6281229710567"; // Nomor WhatsApp
            var message = "Halo kasir, saya ingin bertanya tentang pesanan saya."; // Pesan otomatis
            var encodedMessage = encodeURIComponent(message); // Encode pesan agar tidak error di URL
    
            // Redirect ke WhatsApp
            window.location.href = "https://wa.me/" + phone + "?text=" + encodedMessage;
        });
    </script>
    
    
</div>
@endsection --}}