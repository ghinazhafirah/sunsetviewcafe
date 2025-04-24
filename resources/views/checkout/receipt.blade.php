@extends('layouts.home')


@section('container')
    <div class="container mt-5 text-center">
        <div class="card shadow-lg p-4 text-start">
            <h4 class="text-center">Struk Pembayaran</h4>
            <hr>

            {{-- Kode Transaksi & Waktu --}}
            <div class="d-flex justify-content-between">
                <h6>#Pesanan</h6>
                <div>
                    <strong>{{ $order->kode_transaction }}</strong></h6>
                    <h6 class="text-end"><small>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</small></h6>
                </div>
            </div>
            <hr class="border-warning">


            <p class="text-start mb-1"><strong>Informasi Pemesanan</strong></p>
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
            </table>
            <hr class = "border-warning">
            <p class="text-start mb-1"><strong>Detail Pesanan</strong></p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</strong>
                            </td>
                            <td class="text-center">{{ $item->quantity }}X</td>
                            <td class="text-end">Rp {{ number_format($item->total_menu, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mb-1">
                {{-- <p class="d-flex justify-content-between">Subtotal
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </p>
                <p class="d-flex justify-content-between">Pajak (PB1)
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </p> --}}
                <p class="d-flex justify-content-between">Total Bayar
                    <span><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></span>
                </p>
            </div>

            <br>
            <h6 class="text-start">Metode Pembayaran</h6>
            <td style="text-align: right;">
                <p><strong>{{ strtoupper($order->payment_method) }}</strong> -
                    <span class="badge bg-success">{{ strtoupper($order->status) }}</span>
                </p>
            </td>
            <hr>
            <p class="text-center"><strong>Terima kasih atas pembayaranmu!</strong></p>
            <hr>

            <div class="d-flex gap-2 align-items-center justify-content-center">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Cetak Struk
                </button>
                <a href="{{ route('download.receipt', $order->uuid) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Download PDF
                </a>
                {{-- <a href="{{ url('/') }}" class="btn btn-warning">
                <i class="bi bi-journal"></i> Menu
            </a> --}}
            </div>
        </div>
    </div>
@endsection
