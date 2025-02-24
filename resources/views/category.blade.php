{{-- @dd($posts) --}}

@extends('layouts.home')


@section('container')
    <h1 class="mb-5"> Post Category : {{ $category }}</h1>

    @foreach ($posts as $post)
        <article>
            <h2>
                <a href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
            </h2>
            <p>{{ $post->excerpt }}</p>
            {{-- <h5>{{  $posts}}</h5> --}}

        </article>
    @endforeach
@endsection
