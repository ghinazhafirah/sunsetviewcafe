@extends('layouts.main') {{-- Sesuaikan dengan layout utama Anda --}}

@section('container')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            <img src="/img/scan-qr.png" alt="Scan QR" style="width: 150px; margin-bottom: 20px;">
            <h1 class="mb-4">Selamat Datang di Cafe Kami!</h1>
            <p class="lead">Untuk memulai pesanan, silakan scan QR code di meja Anda.</p>
            <p class="text-muted">Atau, jika Anda ingin mencoba alur pemesanan (untuk demo/pengujian), klik tombol di bawah.</p>
            {{-- Tombol untuk melanjutkan ke menu dengan nomor meja contoh (misal meja 1) --}}
            <a href="{{ route('menu', ['table' => 1]) }}" class="btn btn-primary mt-3">
                <i class="fa fa-cutlery me-2"></i> Lanjutkan ke Menu (Meja 1)
            </a>

            @if (session('error'))
                <div class="alert alert-danger mt-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
@endsection