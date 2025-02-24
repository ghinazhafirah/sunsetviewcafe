@extends('layouts.home')

@section('container')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-6 p-2">
                {{-- <p>By <a href="#" class="text-decoration-none">{{ $post->author->username }}</a> Kategori <a
                            href="/categories/{{ $post->category->slug }}"class="text-decoration-none">{{ $post->category->name }}</a>
                    </p> --}}
                <div class="card">
                    <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $post->category->name }}"
                        class="card-img-top w-70 mx-auto" alt="{{ $post->category->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="card-text"> {!! $post->body !!}</p>
                        <div class="row">
                            <div class="col-md-8 ">
                                @livewire('counter')
                            </div>
                            <div class="col-md-4 w-3">
                                <a href="/menu" class="btn btn-warning w-100 ms-auto ">Back to Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
