@extends('layouts.main')

@section('container')
    <div class="row justify-content-center">
        <div class="col-lg-4 mt-4">
            @if (session()->has('success'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif

            @if (session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('loginError') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif

            <main class="form-signin w-100 m-auto">

                <div class="d-flex justify-content-center">
                    <img src="{{ asset('img/user.jpg') }}" alt="User Image" class="rounded-circle mb-3 " width="100">
                </div>

                <h1 class="h3 mb-3 fw-normal text-center">Please Login</h1>

                <form action="/login" method="post">
                    @csrf
                    {{-- <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Username</label>
              </div> --}}
                    <div class="form-floating">
                        <input type="text" name="name"
                            class="form-control rounded-top @error('name') is-invalid @enderror" id="name"
                            placeholder="Name" autofocus required value="{{ old('email') }}">
                        <label for="name">Username</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control rounded-bottom" id="password"
                            placeholder="Password" required>
                        <label for="password">Password</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-warning w-100 py-2" type="submit">Login</button>
                </form>
                <small class="d-block text-center mt-3">Not Registered?<a href="/register">Register Now!</a></small>
            </main>
        </div>
    </div>
@endsection
