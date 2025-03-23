@extends('layouts.main')

@section('container')

<div class="container mt-5 text-center">
    <div class="card shadow-lg p-4 text-start">
        <h4 class="text-center">Struk Pembayaran</h4>
        <hr>

         {{-- Kode Transaksi & Waktu --}}
         <div class="d-flex justify-content-between">
            <h6>#Pesanan</h6>
            <div>
                <strong>{{ $transaction->kode_transaction }}</strong></h6>
                <h6 class="text-end"><small>{{ date('d-m-Y H:i:s', strtotime($transaction->created_at)) }}</small></h6>
            </div>
        </div>
        <hr>


        <p class="text-start mb-1"><strong>Informasi Pemesanan</strong></p>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 115px;">Nama</td>
                <td style="width: 10px; text-align: center;">:</td>
                <td><strong>{{ $transaction->customer_name }}</strong></td>
            </tr>
            <tr>
                <td>No. WhatsApp</td>
                <td style="text-align: center;">:</td>
                <td><strong>{{ $transaction->customer_whatsapp }}<strong></td>
            </tr>
            <tr>
                <td>Nomor Meja</td>
                <td style="text-align: center;">:</td>
                <td><strong>{{ $transaction->table_number }}</strong></td>
            </tr>
        </table>
    <br>
        <p class="text-start mb-1"><strong>Detail Pesanan</strong></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            {{-- <tbody>
                @foreach ($transaction->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}x</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody> --}}
        </table>

        <div class="mb-1">
            <p class="d-flex justify-content-between">Subtotal 
                <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </p>
            <p class="d-flex justify-content-between">Pajak (PB1)
                <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
            </p>
            <p class="d-flex justify-content-between">Total Bayar
                <span><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></span>
            </p>
        </div>

       <br>
        <h6 class="text-start">Metode Pembayaran</h6>
        <td style="text-align: right;">
            <p><strong>{{ strtoupper($transaction->payment_method) }}</strong> - 
            <span class="badge bg-success">{{ strtoupper($transaction->status) }}</span></p>
        </td>
        <hr>
        <p class="text-center"><strong>Terima kasih atas pembayaranmu!</strong></p>
        <hr>

        <div class="d-flex gap-2 align-items-center justify-content-center">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak Struk
            </button>
            <a href="{{ route('download.receipt', $transaction->uuid) }}" class="btn btn-success">
                <i class="bi bi-download"></i> Download PDF
            </a>
            {{-- <a href="{{ url('/') }}" class="btn btn-warning">
                <i class="bi bi-journal"></i> Menu
            </a> --}}
        </div>
    </div>
</div>
@endsection