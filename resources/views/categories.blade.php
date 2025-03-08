{{-- @dd($posts)

@extends('layouts.home')


@section('container')
    <h1 class="mb-5"> Post Categories</h1>
    <div class="container">
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-4">
                    <a href="/categories/{{ $category->slug }}">
                        <div class="card bd-dark text-white">
                            <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $category->name }}"
                                class="d-block w-10 mx-auto " alt="{{ $category->name }}">
                            <div class="card-img-overlay d-flex align-items-center p-0">
                                <h5 class= "card-title text-center flex-fill p-4 fs-3"
                                    style="background-color:rgba(0, 0, 0, 7)"> {{ $category->name }} </h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection --}}
