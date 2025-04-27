@extends('dashboard.layouts.main') {{-- mengambil menggunakan layout main.blade --}}

@section('container')
    <!-- apapun yang ada didalam section akan menggantikan yield -->


    <!-- TABEL MENU Start -->
    <div class="container">
        <div class="my-5">
            <h2 class="mb-3">{{ $post['title'] }}</h2>
            <a href="/dashboard/posts" class="btn btn-success border-0"><i class="bi bi-arrow-left"></i> Back to all Data
                Menu</a>
            <a href="/dashboard/posts/{{ $post->slug }}/edit" class="btn btn-success bg-warning border-0"><i
                    class="bi bi-pencil-square"></i> Edit</a>
            <form action="/dashboard/posts/{{ $post->slug }}" method="post" class="d-inline">
                @method('delete')
                @csrf
                <button class="btn btn-success bg-danger border-0"
                    onclick="return confirm('Apakah tetap menghapus Menu?')"><i class="bi bi-x-square"></i> Delete</button>
            </form>
        </div>

        {{-- PR FOTO --}}

        <div style="max-height: 350px;" class="bg-light p-2 rounded d-inline-block">
            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->category->name }}"
                    class="img-fluid small-img">
            @else
                {{-- tambahan ketika menu gada gambarnya --}}
                <img src="{{ asset('img/notavailable.png') }}" alt="Image Not Available" class="img-fluid small-img">
            @endif
        </div>

        <p class="mt-2 fw-bold"><strong>Harga:</strong> Rp {{ number_format($post->price, 0, ',', '.') }}</p>

        <p class="mb-2">
            {!! $post->body !!}
        </p>
    </div>
@endsection
