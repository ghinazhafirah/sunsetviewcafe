@extends('dashboard.layouts.main')

@section('container')
    <div class="container mt-3">
        <a href="{{ route('dashboard.index') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i></a>
        <br> </br>

        <h4 class="mb-3">Konfirmasi Pembayaran</h4>

        <div class="card shadow-lg p-4">
            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p><strong>No. WA:</strong> {{ $order->customer_whatsapp }}</p>
            <p><strong>Total:</strong> Rp. {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Meja:</strong> {{ $order->table_number }}</p>
            <p><strong>Status:</strong>
                @if ($order->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif ($order->status == 'paid')
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-danger">Failed</span>
                @endif
            </p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>

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
                    {{-- @foreach ($cartItems as $cartItem)                    <tr>
                        <td>{{ $cartItem->post->title ?? 'Menu Tidak Ditemukan' }}</td>
                        <td class="text-center">{{ $cartItem->quantity }}X</td>
                        <td class="text-end">Rp {{ number_format($cartItem->total_menu, 0, ',', '.') }}</td>
                    </tr>
                @endforeach --}}

                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                {{-- Debugging relasi --}}
                                {{-- @if ($item->post)
                                    {{ $item->post->title }}
                                @else
                                    <span class="text-danger">Menu tidak ditemukan (ID: {{ $item->posts_id }})</span>
                                @endif --}}
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
