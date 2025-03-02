{{-- @dd($posts) --}}

@extends('layouts.home')


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
                    <div class="card-body">
                        <div class="row g-0 align-items-center border-bottom border-warning pb-2">
                            <div class="col-4">
                                <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $post->category->name }}"
                                    class="card-img-top img-fluid p-2 rounded" alt="{{ $post->category->name }}">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-0">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <p class="card-text">{{ $post->excerpt }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Rp {{ number_format($post->harga, 0, ',', '.') }},000</span>
                                        <a href="/posts/{{ $post->slug }}" class="btn btn-warning">Add</a>
                                        {{-- <i class='far fa-heart'></i> --}}
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
