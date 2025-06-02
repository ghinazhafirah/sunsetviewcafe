@extends('layouts.main')

@section('container')
    <div class="container text-center mt-5 p-1">
        <h2 class="text-success">Pesanan Anda Berhasil!</h2>
        <br>
        @livewire('checkout-status', ['orderId' => $order->id])
    </div>
@endsection
