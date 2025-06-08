@extends('dashboard.layouts.main')

<style>
    tr {
        padding-bottom: 2px;
    }
</style>
@section('container')
    <div class="container mt-3">
        <a href="{{ route('dashboard.index') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i></a>
        <br> </br>

        <h4 class="mb-3">Konfirmasi Pembayaran</h4>

        <div class="card lg p-4">
            <table style="width: 100%; border-collapse: collapse; text-color:black;">
                <tr>
                    <td style="width: 165px;">Nama</td>
                    <td style="width: 10px; text-align: center;">:</td>
                    <td><strong>{{ $order->customer_name }}</strong></td>
                </tr>
                <tr>
                    <td>No. WhatsApp</td>
                    <td style="text-align: center;">:</td>
                    <td><strong>{{ $order->customer_whatsapp }}</strong></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td style="text-align: center;">:</td>
                    <td><strong>{{ $order->customer_email }}</strong></td>
                </tr>
                <tr>
                    <td>Nomor Meja</td>
                    <td style="text-align: center;">:</td>
                    <td><strong>{{ $order->table_number }}</strong></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td style="text-align: center;">:</td>
                    <td><strong> Rp. {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td style="text-align: center;">:</td>
                    <td><strong>
                            @if ($order->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif ($order->status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </strong></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td style="text-align: center;">:</td>
                    <td><strong> {{ ucfirst($order->payment_method) }}</strong></td>
                </tr>
            </table>

            <h5 class="mt-4">Rincian Pesanan</h5>
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
                                @if (!empty($item->note))
                                    <textarea class="form-control border-warning mt-2" readonly style="height: auto; overflow-y: hidden; line-height: 1;">{{ trim($item->note) }}</textarea>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}X</td>
                            <td class="text-end">Rp {{ number_format($item->total_menu, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <p class="d-flex justify-content-between"><strong>Total Bayar</strong>
                <span><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></span>
            </p>

            @if ($order->status == 'pending')
                <form action="{{ route('dashboard.confirmPayment', $order->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
                </form>
            @endif
        </div>
    </div>
@endsection
