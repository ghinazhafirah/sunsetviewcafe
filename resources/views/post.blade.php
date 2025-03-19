{{-- <pre>{{ var_dump($post) }}</pre> --}}


@extends('layouts.home')

@section('container')
    {{-- container setinggi layar penuh --}}
    <div class="container d-flex min-vh-100">
        {{-- konten utama diatas, tombol dibawah --}}
        <div class="row justify-content-center flex-grow-1">
            {{-- tombol absolut, ga keluar container --}}
            <div class="col-md-6 p-2 d-flex flex-column position-relative">
                <div class="card h-100">
                    <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s=dd060fe209b4a56733a1dcc9b5aea53a{{ $post->category->name }}"
                        class="card-img-top w-70 mx-auto" alt="{{ $post->category->name }}">
                    <a href="{{ route('menu', ['table' => $tableNumber]) }}"
                        class="btn btn-warning position-absolute end-0 m-2"><i class="fa fa-close"></i></a>
                    <div class="card-body d-flex flex-column flex-grow-1">
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="card-text border-bottom border-warning pb-2"> {!! $post->body !!}</p>
                        <div class="mt-auto">
                            <div class="row d-flex pb-2">
                                <div class="col-md-12 col-12">
                                    <div>
                                        @livewire('counter', ['postId' => $post->id])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
