@extends('layouts.main')

@section('container')
    <div class="container text-center mt-5">
        <h2 class="text-success">Pesanan Anda Berhasil!</h2>

        @livewire('checkout-status', ['orderId' => $order->id])
    </div>
@endsection
