@extends('layouts.home')

@section('container')
    <h1 class="container-fluid"></h1>
    {{-- menghitung jumlah post --}}
    @if ($posts->count())
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <div class="card-body text-center mb-2">
                    <div id="carouselExampleCaptions" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            @foreach ($images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $posts[0]->category->name }}"
                                        class="d-block w-70 mx-auto" alt="{{ $posts[0]->category->name }}">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5 class="text-dark">{{ $posts[0]->title }}</h5>
                                        {{-- <p class="text-dark"> {{ $posts[0]->excerpt }}</p> --}}
                                        <a href="/posts/{{ $posts[0]->slug }}"
                                            class="text-decoration-none btn btn-warning text-dark">Read
                                            more..</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
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
                @livewire('category-filter', ['tableNumber' => session('tableNumber')])
            </div>
        </div>
    </div>
@endsection
