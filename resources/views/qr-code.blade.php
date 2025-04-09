@extends('dashboard.layouts.main') {{-- mengambil menggunakan layout main.blade --}}

@section('container')
    <h2 class="text-center">Generate QR Code Meja</h2>

    <!-- Form untuk input jumlah meja -->
    <form method="POST" action="{{ route('generate.qr') }}" class="text-center mb-4">
        @csrf
        <div class="form-group">
            <label for="jumlah_meja">Masukkan Jumlah Meja:</label>
            <input type="number" id="jumlah_meja" name="jumlah_meja" class="form-control w-25 mx-auto" required min="1">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Generate QR Code</button>
    </form>

    @if (!empty($qrCodes))
        <h3 class="text-center">QR Code untuk {{ $jumlahMeja }} Meja</h3>
        <div class="row">
            @foreach ($qrCodes as $tableNumber => $qrCode)
                <div class="col-md-4 text-center mb-4">
                    <h4>Meja {{ $tableNumber }}</h4>
                    <div id="qr-{{ $tableNumber }}" class="qr-container">
                        {!! $qrCode !!}
                    </div>
                    <button class="btn btn-success mt-2"
                        onclick="downloadQR('qr-{{ $tableNumber }}', 'Meja_{{ $tableNumber }}.png')">
                        Download QR
                    </button>
                </div>
            @endforeach
        </div>
    @endif

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
{{-- @endsection --}}
@endsection
