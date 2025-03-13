@extends('layouts.home')


@section('container')
    <h2 class="text-center">Scan QR Code untuk Meja</h2>
    <div class="row">
        @foreach ($qrCodes as $tableNumber => $qrCode)
            <div class="col-md-4 text-center mb-4">
                <h4>Meja {{ $tableNumber }}</h4>
                {!! $qrCode !!}
            </div>
        @endforeach
    </div>
@endsection
