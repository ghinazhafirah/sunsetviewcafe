@extends('layouts.main')

@section('container')
    {{-- Pembungkus utama agar lebar konsisten dengan cart dan checkout --}}
    <div class="row justify-content-center mt-2">
        <div class="card col-12 col-md-10 col-lg-8 p-0" style="max-width: 700px; width: 100%;">
            <div class="cart-table-area section-padding-50 shadow-lg p-4 text-start">
                <div class="container">
                    {{-- Konten asli dari halaman struk pembayaran dimulai di sini --}}
                        <h4 class="text-center">Setruk Pembayaran</h4>
                        <hr>

                        {{-- Kode Transaksi & Waktu --}}
                        <div class="d-flex justify-content-between text-warning">
                            <h6>#Pesanan</h6>
                            <div>
                                <strong>{{ $order->order_id }}</strong></h6>
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
                        <hr class="border-warning">
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
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td>
                                            {{ $item->post->title ?? 'Menu Tidak Ditemukan' }}
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}X</td>
                                        <td class="text-end">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mb-1">
                            <p class="d-flex justify-content-between"><strong>Total Bayar</strong>
                                <span><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></span>
                            </p>
                        </div>
                        <hr class="border-warning">
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
                            <!-- <a href="https://docs.google.com/forms/u/0/d/e/1FAIpQLSe9blhP-_CEpY1gERjdqgAyaEBewHb9Hw5ZrzegrPZI3PbY_A/formResponse" target="_blank" class="btn btn-primary text-white">
                                <i class="bi bi-clipboard-check"></i> Survei</a> -->
                            <a href="{{ route('download.receipt', $order->uuid) }}" class="btn btn-success">
                                <i class="bi bi-download"></i> Download Setruk
                            </a>
                             <a  href="/menu" class="btn btn-primary">Menu</a>
                        </div>
                    </div>
                    {{-- Konten asli dari halaman struk pembayaran berakhir di sini --}}
                </div>
            </div>
        </div>
    </div>
@endsection