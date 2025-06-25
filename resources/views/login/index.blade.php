@extends('layouts.main') {{-- Tetap gunakan layouts.main seperti biasa --}}

@section('container')
    {{-- Tambahkan <style> block di sini untuk CSS khusus halaman login --}}
    <style>
        /* Pastikan elemen ini benar-benar mengisi area yang tersedia */
        /* Kita perlu mengatasi padding dari div.container yang di-generate oleh layouts.main */
        body.login-page-body #login-full-cover {
            /* Hapus padding dan margin yang mungkin diberikan oleh Bootstrap's .container atau parent lainnya */
            padding: 0 !important;
            margin: 0 !important;
            /* Pastikan elemen ini mengambil lebar penuh yang tersedia dari parent-nya */
            width: 100vw; /* Mengambil 100% lebar viewport */
            height: 100vh; /* Mengambil 100% tinggi viewport */
            /* Posisikan secara absolut untuk mengabaikan flow dokumen dan mengisi penuh */
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1; /* Pastikan di bawah alert, tapi di atas elemen lain jika ada */
            overflow: hidden; /* Sembunyikan jika ada bagian yang melebihi viewport */
        }

        /* Tambahkan class ke body untuk menargetkan hanya di halaman login */
        /* Ini agar style ini hanya aktif saat body memiliki class 'login-page-body' */
        body.login-page-body {
            overflow: hidden; /* Sembunyikan scrollbar jika body tidak boleh di-scroll */
        }
        /* Pastikan tidak ada padding pada body jika ada */
        body.login-page-body > .container {
             padding: 0 !important;
             margin: 0 !important;
             max-width: none !important; /* Hapus batasan max-width dari .container */
        }
    </style>

    {{-- Background Layer --}}
    {{-- Tambahkan id="login-full-cover" pada div background Anda --}}
    {{-- Dan tambahkan class "login-page-wrapper" jika ada elemen lain yang perlu diatur --}}
    <div id="login-full-cover" class="d-flex justify-content-center align-items-center"
        style="background-image: url('{{ asset('img/sunsetview.avif') }}'); background-size: cover; background-position: center;">
        {{-- background-opacity: 0.1; tidak bekerja langsung di CSS, biasanya pakai rgba() atau elemen overlay --}}

        {{-- ? Login Form --}}
        {{-- Hapus class w-100 w-md-50 w-lg-25 font-sans d-flex flex-column justify-content-center align-items-center --}}
        {{-- dan ganti dengan yang lebih sederhana, biarkan parent mengatur centering --}}
        <div class="rounded-3" style="width: 90%; max-width: 400px; z-index: 2;"> {{-- Beri z-index lebih tinggi agar form terlihat --}}
            {{-- Hapus div h-full col-12 col-md-6 col-lg-4 justify-center items-center p-4 --}}
            <form action="/login" method="post"
                class="w-100 p-4 rounded bg-white shadow"> {{-- Gunakan w-100 agar form mengisi lebar parent --}}
                @csrf
                <div class="d-flex justify-content-center">
                  <img src="{{ asset('img/user.jpg') }}" alt="User Image" class="rounded-circle mb-3 " width="100">
                </div>
                <h1 class="fs-4 mb-3 text-center font-sans font-bold">Login</h1>

                <div class="mb-3">
                    <label for="name" class="form-label">Username</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        placeholder="Enter your username..." required value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter your password..." required>
                </div>

                <button class="w-100 btn btn-warning rounded-3 font-bold" type="submit">Login</button>

                <!-- <div class="d-flex flex-row justify-content-center gap-2 font-sans mt-3">
                    <p>Not Registered?</p>
                    <a href="/register" class="hover:underline font-bold">Register Now!</a>
                </div> -->
            </form>
        </div>
    </div>

    {{-- Alert Messages Container --}}
    {{-- Pastikan ini memiliki z-index yang lebih tinggi dari background layer --}}
    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 1050; width: auto; max-width: 350px;">
        @if (session()->has('success'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-bottom: 10px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif

        @if (session()->has('loginError'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 10px;">
                {{ session('loginError') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    </div>

    {{-- Script untuk menambahkan class ke body --}}
    <script>
        document.body.classList.add('login-page-body');
    </script>
@endsection