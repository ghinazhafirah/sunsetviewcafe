@extends('layouts.home')


@section('container')
    <h1 class="container-fluid mt-2 p-0"></h1>
    <div class="row justify-content-center">
        <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
            <div class="cart-table-area section-padding-50">
                <div class="container-fluid">
                    <div class="row py-3">
                        <div class="col-md-4 col-3 p-0">
                            <a href="/menu" class="btn btn-warning"><i class="fa fa-angle-left"></i></a>
                        </div>
                        <div class="col-md-4 col-6 p-0">
                            <div class="cart-title">
                                {{-- <h4>Pesanan Meja : {{ $tableNumber ?? 'Tidak Diketahui' }}</h4> --}}

                                @if (is_numeric($tableNumber))
                                    <h4>Pesanan Meja : {{ $tableNumber }}</h4>
                                @else
                                    <h4>Pesanan Meja : Tidak Diketahui</h4>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8 p-0">
                            <div class="cart-table clearfix">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody wire:ignore.self>
                                        @foreach ($cart as $item)
                                            <tr wire:key="cart-item-{{ $item->id }}">
                                                <td>{{ $item->post->title }}</td>
                                                <td>
                                                    @livewire('counter-cart', ['cartId' => $item->id, 'jumlahMenu' => $item->jumlah_menu, 'totalMenu' => $item->total_menu])
                                                </td>
                                                <td>Rp {{ number_format($item->total_menu, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 mt-auto pb-3">
                            <div class="cart-summary">
                                <h5>Cart Total</h5>
                                <ul class="summary-table p-0">
                                    <li><span>Total:</span> <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                                <div class="cart-btn mt-70">
                                    <a href="/checkout" class="btn amado-btn w-100">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
