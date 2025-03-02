@extends('layouts.home')


@section('container')
    <div class="container-fluid mt-2 p-0">
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
                                    <h2>Shopping Cart</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-8 p-0">
                                <div class="cart-table clearfix">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                {{-- <th></th> --}}
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $item)
                                                <tr>
                                                    <td>{{ $item->post->title }}</td>
                                                    <td>{{ $item->jumlah_menu }}</td>
                                                    <td>Rp {{ number_format($item->total_menu, 0, ',', '.') }}</td>
                                                    {{-- <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="row justify-content-center"> --}}
                            <div class="col-12 col-lg-4 mt-auto pb-3">
                                <div class="cart-summary clearfix">
                                    <h5>Cart Total</h5>
                                    <ul class="summary-table">
                                        <li><span>Subtotal:</span> <span>Rp
                                                {{ number_format($subtotal, 0, ',', '.') }}</span></li>
                                        <li><span>Tax:</span> <span>Free</span></li>
                                        <li><span>Total:</span> <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </li>
                                    </ul>
                                    <div class="cart-btn mt-70">
                                        <a href="/checkout" class="btn amado-btn w-100">Next</a>
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
