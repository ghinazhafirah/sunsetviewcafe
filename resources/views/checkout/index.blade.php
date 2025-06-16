@extends('layouts.main')

@section('container')
    {{-- Pembungkus utama untuk menyamai struktur halaman cart --}}
    <div class="row justify-content-center mt-2"> {{-- Tambahkan mt-2 agar ada sedikit jarak dari atas --}}
        <div class="card col-12 col-md-10 col-lg-8 p-0" style="max-width: 700px; width: 100%;">
            <div class="cart-table-area section-padding-50">
                <div class="container">
                        <div class="row py-3 px-2 mx-0">
                            <div class="col-md-4 col-3 p-0">
                            <a href="{{ route('cart.show', ['table' => session('tableNumber')]) }}" class="btn btn-warning"><i
                                        class="fa fa-angle-left"></i></a> </div>
                         <h2 class="h4 mb-0 text-center flex-grow-1 ms-3 me-3">Formulir Checkout</h2>
                         {{-- Centered title --}}
                        <div></div> {{-- Spacer for balanced flex layout --}}
                    </div>

                    <p class="lead text-center mb-5 text-muted">
                        Harap memeriksa data dengan benar sebelum melakukan pembayaran!
                    </p>



                        <div class="row g-4">
                            {{-- Order Summary Section (Right Column on Desktop, Top on Mobile) --}}
                        <div class="col-md-5 col-lg-4 order-md-last">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-warning fw-bold">Pesanan Meja</span>
                                @if (session()->has('tableNumber') && is_numeric(session('tableNumber')))
                                    <span class="badge bg-warning text-white rounded-pill py-2 px-3 fs-6">
                                        {{ session('tableNumber') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill py-2 px-3 fs-6">
                                        Tidak Ada
                                    </span>
                                @endif
                            </h4>

                            <ul class="list-group mb-3 shadow-sm"> {{-- Added shadow to list group --}}
                                @forelse ($cart as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-start lh-sm">
                                        <div class="me-auto"> {{-- Pushes content to the left --}}
                                            <h6 class="my-0 text-dark">{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</h6>
                                            <small class="text-muted">{{ $item['quantity'] }} x</small>
                                            @if (!empty($item->note))
                                                <textarea class="form-control border-warning mt-2 form-control-sm" readonly
                                                    style="height: auto; min-height: 38px; overflow-y: hidden; line-height: 1.2; font-size: 0.875em;">{{ trim($item->note) }}</textarea>
                                            @endif
                                        </div>
                                        <span class="text-nowrap ms-2">Rp {{ number_format($item['total_menu'], 0, ',', '.') }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Keranjang kosong.</li>
                                @endforelse

                                <li class="list-group-item d-flex justify-content-between bg-light fw-bold"> {{-- Highlight total --}}
                                    <span>Total</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>


                        {{-- Customer Data and Payment Section (Left Column on Desktop, Bottom on Mobile) --}}
                        <div class="col-md-7 col-lg-8">
                            <h4 class="mb-3">Data Pelanggan</h4>
                            <form action="{{ route('checkout.storeCustomerData') }}" method="POST" class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="customer_name" class="form-label">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" id="customer_name"
                                            value="{{ old('customer_name') }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Nama pelanggan wajib diisi.</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="customer_whatsapp" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="customer_whatsapp"
                                            id="customer_whatsapp"
                                            placeholder="Cth: 081234567890"
                                            value="{{ old('customer_whatsapp') }}"
                                            required pattern="[0-9]{10,15}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        @error('customer_whatsapp')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Harap masukkan nomor WhatsApp yang valid (hanya angka, 10-15 digit).</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="customer_email" class="form-label">Email <span class="text-muted">(untuk pengiriman struk)</span></label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email"
                                            placeholder="nama@gmail.com" value="{{ old('customer_email') }}">
                                        @error('customer_email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Harap masukkan alamat email yang valid.</div>
                                        @enderror
                                    </div>
                                </div>



                                            <hr class="my-4">
                                            <h4 class="mb-3">Payment</h4>
                                            <div class="d-flex gap-3 mb-3">
                                                <input type="hidden" name="payment_method" value="cash">
                                                <button class="btn btn-secondary btn-lg flex-fill"
                                                    type="submit">Cash</button>
                                                <button class="btn btn-warning btn-lg flex-fill" type="submit"
                                                    name="payment_method" value="digital">
                                                    Digital
                                                </button>
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