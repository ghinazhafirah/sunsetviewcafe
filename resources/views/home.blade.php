@extends('layouts.home')

{{-- @php
    dump(session('tableNumber')); // Apakah session masih ada setelah redirect?
@endphp --}}


@section('container')
    <img src="img/{{ $image }}" alt="" width="150" />
    <h3>{{ $name }}</h3>
    <p>{{ $email }}</p>
    <h1>Halaman Home</h1>
@endsection
