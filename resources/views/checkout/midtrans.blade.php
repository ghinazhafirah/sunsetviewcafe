@extends('layouts.home')

@section('container')
    <div class="container mt-5 text-center">
        <h3>Memproses Pembayaran Anda...</h3>

        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script type="text/javascript">
            window.snap.pay("{{ $snapToken }}", {
                onSuccess: function(result) {
                    window.location.href = "{{ route('checkout.success', ['uuid' => $order->uuid]) }}";
                },
                onPending: function(result) {
                    alert("Transaksi sedang diproses.");
                },
                onError: function(result) {
                    alert("Terjadi kesalahan saat pembayaran.");
                },
                onClose: function() {
                    alert("Anda menutup popup pembayaran.");
                }
            });
        </script>
        <form action="{{ route('checkout.changePayment') }}" method="POST">
            @csrf <!-- CSRF token is crucial for POST requests in Laravel -->
            <button type="submit" class="btn btn-primary">
                Ubah Metode Pembayaran
            </button>
        </form>
    </div>
@endsection
