{{-- @dd($posts) --}}

@extends('layouts.home')


@section('container')
    <h1 class="container-fluid"></h1>

    {{-- menghitung jumlah post --}}
    @if ($posts->count())
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <div class="card-body text-center">
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
                        {{-- @livewire('counter') Tombol Increment & Decrement --}}
                        <a href="/posts/{{ $posts[0]->slug }}" class="text-decoration-none bg-warning btn btn-warning">Read
                            more..</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center fs-4">No post found.</p>
    @endif



    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills">
                        @php
                            // Mengambil daftar kategori unik dari posts & mengurutkannya
                            $categories = $posts->pluck('category')->unique()->sortBy('name');
                        @endphp

                        @foreach ($categories as $category)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('categories/' . $category->slug) ? 'active' : '' }}"
                                    href="/categories/{{ $category->slug }}"
                                    class="text-decoration-none bg-warning text-light">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @foreach ($posts as $post)
                    <div class="card-body mb-2 px-2 p-0">
                        <div class="col-12 mb-2 mt-2">
                            <div class="card bg-warning">
                                {{-- <div class="position-absolute px-3 py-2"
                                    style="background-color: rgba(0, 0, 0, 0.7)"> <a
                                        href="/categories/{{ $post->category->slug }}" class="text-white text-decoration-none">{{ $post->category->name }}</a>
                                </div> --}}
                                <div class="row g-0">
                                    <div class="col-md-4 d-flex align-items-center">
                                        <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $post->category->name }}"
                                            class="card-img-top w-70 mx-auto p-2 img-fluid"
                                            alt="{{ $post->category->name }}">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $post->title }}</h5>
                                            <p class="card-text">{{ $post->excerpt }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="/posts/{{ $post->slug }}"
                                                    class="btn btn-light text-dark">Add</a>
                                                <div class="ms-auto">
                                                    {{-- <i class='far fa-thumbs-up' style='font-size:24px;'></i> --}}
                                                    <i class='far fa-heart' style='font-size:24px;'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
