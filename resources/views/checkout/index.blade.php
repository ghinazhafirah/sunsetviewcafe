@extends('layouts.home')

@section('container')
    {{-- <body class="bg-body-tertiary"> --}}
    <div class="row justify-content-center m-2">
        <div class="card col-12 col-md-10 col-lg-8 p-0" style="max-width: 700px; width: 100%;">
            <div class="cart-table-area section-padding-50">
                <div class="container">
                    <main>
                        <div class="py-5 text-center">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="{{ route('cart.show', ['table' => $tableNumber]) }}" class="btn btn-warning"><i
                                        class="fa fa-angle-left"></i></a>
                                <div class="flex-grow-1 text-center">
                                    <h2 class="m-0">Checkout Form</h2>
                                </div>
                            </div>
                            <p class="lead mt-3">Harap memeriksa data dengan benar sebelum melakukan pembayaran!</p>
                        </div>


                        <div class="row g-5">
                            <div class="col-md-5 col-lg-4 order-md-last">
                                <h4 class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-warning">Pesanan</span>
                                    @if (session()->has('tableNumber'))
                                        <div class="badge bg-primary bg-warning rounded-square">
                                            <h6>{{ session('tableNumber') ?? 'Tidak ada' }}</h6>
                                        </div>
                                    @endif
                                </h4>

                                {{-- TAMPILKAN CART --}}
                                <ul class="list-group mb-3">
                                    @foreach ($cart as $item)
                                        <li class="list-group-item d-flex justify-content-between lh-sm">
                                            <div>
                                                <h6 class="my-0">{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</h6>
                                                <small class="text-body-secondary">{{ $item['quantity'] }}X</small>
                                            </div>
                                            <span class="text-body-secondary">Rp
                                                {{ number_format($item['total_menu'], 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach

                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total</span>
                                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </li>
                                </ul>


                            </div>

                            <div class="col-md-7 col-lg-8">
                                <form action="{{ route('checkout.storeCustomerData') }}" method="POST">
                                    @csrf
                                    {{-- DATA PELANGGAN --}}
                                    <h4 class="mb-3">Data Pelanggan</h4>
                                    <form class="needs-validation" novalidate>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="customer_name" class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="customer_name"
                                                    value="" required>
                                                <div class="invalid-feedback">
                                                    Valid first name is required.
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="customer_whatsapp" class="form-label">Nomor WhatsApp</label>
                                                <input type="tel" class="form-control" name="customer_whatsapp"
                                                    id="customer_whatsapp"
                                                    placeholder="*Harap masukkan nomor WhatsApp dengan benar untuk pengiriman struk"
                                                    required pattern="[0-9]+"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                <div class="invalid-feedback">
                                                    *Harap masukkan nomor WhatsApp dengan benar (hanya angka, 10-15 digit).
                                                </div>

                                                <hr class="my-4">
                                                <h4 class="mb-3">Payment</h4>
                                                <div class="d-flex gap-3 mb-2">
                                                    <input type="hidden" name="payment_method" value="cash">
                                                    <button class="btn btn-secondary btn-lg flex-fill"
                                                        type="submit">Cash</button>
                                                    {{-- <button class="btn btn-warning btn-lg flex-fill" value="digital"
                                                        type="submit">Digital</button> --}}
                                                    <button class="btn btn-warning btn-lg flex-fill" type="submit"
                                                        name="payment_method" value="digital">
                                                        Digital
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </form>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
