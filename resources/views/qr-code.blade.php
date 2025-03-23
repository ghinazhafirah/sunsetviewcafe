{{-- @extends('layouts.home')


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
@endsection --}}

@extends('layouts.home')

@section('container')
    <h2 class="text-center">Scan QR Code untuk Meja</h2>
    <div class="row">
        @foreach ($qrCodes as $tableNumber => $qrCode)
            <div class="col-md-4 text-center mb-4">
                <h4>Meja {{ $tableNumber }}</h4>
                <div id="qr-{{ $tableNumber }}" class="qr-container">
                    {!! $qrCode !!}
                </div>
                <button class="btn btn-success mt-2" onclick="downloadQR('qr-{{ $tableNumber }}', 'Meja_{{ $tableNumber }}.png')">Download QR</button>
            </div>
        @endforeach
    </div>

    <!-- Tambahkan Library html2canvas -->
    <script>
        function downloadQR(divId, filename) {
            const qrElement = document.getElementById(divId);

            html2canvas(qrElement).then(canvas => {
                const link = document.createElement('a');
                link.href = canvas.toDataURL("image/png");
                link.download = filename;
                link.click();
            });
        }
    </script>
@endsection
