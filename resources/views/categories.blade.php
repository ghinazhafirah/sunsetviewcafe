{{-- @dd($posts) --}}

@extends('layouts.home')


@section('container')
    <h1 class="mb-5"> Post Categories</h1>
    <div class="container">
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-4 mx-2">
                    <a href="/categories/{{ $category->slug }}"></a>
                    <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $category->name }}"
                        class="d-block w-10 mx-auto " alt="{{ $category->name }}">
                    <div class="card text-bg-dark">
                        <div class="position-absolute px-3 py-2" style="background-color: rgba(0, 0, 0, 0.7)"> <a
                                href="/categories/{{ $category->slug }}"
                                class="text-white text-decoration-none">{{ $category->name }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
