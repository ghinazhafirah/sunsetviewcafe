@extends('layouts.main')

@section('container')
    <h1 class="mb-5">Post Categories</h1>
    <div class="container">
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-4 mb-3">
                    <a href="/categories/{{ $category->slug }}" class="text-decoration-none">
                        <div class="card text-bg-dark">
                            @if ($category->image)
                                {{-- Menggunakan $category, bukan $posts --}}
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="img-fluid menu-image">
                            @else
                                {{-- Jika kategori tidak memiliki gambar, gunakan placeholder --}}
                                <img src="{{ asset('img/notavailable.png') }}" alt="Image Not Available"
                                    class="img-fluid menu-image">
                            @endif
                            <div class="card-img-overlay d-flex align-items-center p-0">
                                <h5 class="card-title text-center flex-fill p-3 fs-3"
                                    style="background-color: rgba(0,0,0,0.7);">
                                    {{ $category->name }}
                                </h5>
                                <tr>
                                    <td>{{ $post->name }}</td>
                                    <h5 class="fw-bold"><strong></strong> Rp {{ number_format($post->price, 2, ',', '.') }}
                                    </h5>
                                </tr>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
