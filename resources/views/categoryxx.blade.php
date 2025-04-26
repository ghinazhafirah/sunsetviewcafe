{{-- @dd($posts) --}}

@extends('layouts.main')


@section('container')
    {{-- @foreach ($posts as $post)
        <article>
            <h2>
                <a href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
            </h2>
            <p>{{ $post->excerpt }}</p>
            {{-- <h5>{{  $posts}}</h5>
        </article>
    @endforeach --}}

    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <h2 class="my-2 text-center"> {{ $category }}</h2>
                @foreach ($posts as $post)
                    <div class="card-body mb-2 px-2 p-0">
                        <div class="col-12 mb-2 mt-2">
                            <div class="card bg-warning">
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
                                                <a href="/posts/{{ $post->slug }}" class="btn btn-light text-dark">Add</a>
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