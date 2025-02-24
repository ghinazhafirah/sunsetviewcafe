@extends('layouts.main')


@section('container')

    <h1>Halaman Formulir Menu</h1>
    <h3>{{ $name }}</h3>
    <p>{{ $email }}</p>
    <img src="img/{{ $image  }}" alt="" width="150" />

@endsection

