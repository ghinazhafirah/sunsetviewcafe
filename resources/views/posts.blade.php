{{-- @dd($posts) --}}

@extends('layouts.home')


@section('container')
    <h1 class="container-fluid"></h1>

    {{-- menghitung jumlah post --}}
    @if ($posts->count())
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <div class="card-body text-center mb-2">
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    {{-- <img src="{{ asset('img/' . $image) }}" class="d-block w-10 mx-auto" alt="..."> --}}
                                    <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $posts[0]->category->name }}"
                                        class="d-block w-3 mx-auto img-fluid" alt="{{ $posts[0]->category->name }}">
                                    {{-- <img src="{{ asset('storage/' . $posts[0]->image) }}" alt="{{ $posts[0]->category->name }}" class="img-fluid"> --}}
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <h3 class="card-title">{{ $posts[0]->title }}</h3>
                        <p class="card-text"> {{ $posts[0]->excerpt }}</p>
                        <a href="/posts/{{ $posts[0]->slug }}"
                            class="text-decoration-none btn btn-outline-warning text-dark">Read
                            more..</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center fs-4">No post found.</p>
    @endif

    <div class="container-fluid mt-2 p-0">
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                @livewire('category-filter') {{-- Menampilkan kategori dengan Livewire --}}
            </div>
        </div>
    </div>
@endsection
