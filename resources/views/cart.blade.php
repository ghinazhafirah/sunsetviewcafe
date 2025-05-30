@extends('layouts.home')


@section('container')
    <h1 class="container-fluid mt-2 p-0"></h1>
    <div class="row justify-content-center mt-2">
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
                        <div class="col-12 col-lg-11 p-2 ">
                            @livewire('cart-list', ['tableNumber' => $tableNumber])
                        </div>

                        {{-- Gantikan summary manual dengan komponen Livewire --}}
                        <div class="col-12 col-lg-11 pb-3">
                            @livewire('cart-summary', ['tableNumber' => $tableNumber])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('confirmDelete', function(payload) {
                const cartId = payload.cartId;

                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Apakah Anda yakin ingin menghapusnya?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('removeFromCart', {
                            cartId: cartId
                        });
                    }
                });
            });
        });
    </script> --}}

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirmDelete', (payload) => {
                const cartId = payload.cartId;

                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Apakah Anda yakin ingin menghapus item ini dari keranjang?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Merah untuk "Hapus"
                    cancelButtonColor: '#3085d6', // Biru untuk "Batal"
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // **PERBAIKAN UTAMA DI SINI:**
                        // Gunakan Livewire.dispatch() untuk memicu event ke komponen CartList
                        Livewire.dispatch('deleteConfirmed', {
                            cartId: cartId
                        }); // Kirim event baru
                        Swal.fire(
                            'Dihapus!',
                            'Item berhasil dihapus.',
                            'success'
                        );
                    }
                });
            });
        });
    </script>
