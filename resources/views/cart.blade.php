@extends('layouts.home')


@section('container')
    <h1 class="container-fluid mt-2 p-0"></h1>
    <div class="row justify-content-center">
        <div class="card col-12 col-md-10 col-lg-8 p-0" style="max-width: 700px; width: 100%;">
            <div class="cart-table-area section-padding-50">
                <div class="container">
                    <div class="row py-3 px-2 mx-0">
                        <div class="col-md-4 col-3 p-0">
                            <a href="{{ route('menu', ['table' => session('tableNumber')]) }}" class="btn btn-warning"><i
                                    class="fa fa-angle-left"></i></a>
                        </div>
                        <div class="col-md-4 col-8 p-0 d-flex align-items-center justify-content-center">
                            <div class="cart-title">
                                @if (is_numeric($tableNumber))
                                    <h4>Pesanan Meja : {{ $tableNumber }}</h4>
                                @else
                                    <h4>Pesanan Meja : Tidak Diketahui</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-11 p-0 ">
                            <div class="cart-table clearfix">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody wire:ignore.self>
                                        @foreach ($cart as $item)
                                            <tr wire:key="cart-item-{{ $item->id }}">
                                                <td>
                                                    <strong>{{ $item->post->title }}</strong>
                                                    @if (!empty($item->note))
                                                        <textarea class="form-control border-warning mt-2" readonly
                                                            style="height: auto; overflow-y: hidden; padding: 1; line-height: 1;">{{ trim($item->note) }}</textarea>
                                                    @endif
                                                </td>
                                                <td class="text-center ">
                                                    @livewire('counter-cart', ['cartId' => $item->id, 'quantity' => $item->quantity, 'totalMenu' => $item->total_menu])
                                                </td>
                                                <td class="text-end">Rp {{ number_format($item->total_menu, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-lg-11 pb-3">
                            <div class="cart-summary">
                                <h5>Cart Total</h5>
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-6 col-4">
                                        <span>Total:</span>
                                    </div>
                                    <div class="col-md-6 col-8 text-end">
                                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="cart-btn mt-3">
                                        <a href="{{ route('checkout.index', ['table' => $tableNumber]) }}" class="btn amado-btn w-100">Next</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection