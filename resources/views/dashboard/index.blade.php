@extends('dashboard.layouts.main')  {{-- mengambil menggunakan layout main.blade --}}

@section('container') <!-- apapun yang ada didalam section akan menggantikan yield -->

<!-- Gunakan komponen Livewire untuk menampilkan data transaksi -->
@livewire('order-table')  
@endsection