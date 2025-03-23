<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; }
        .text-center { text-align: center; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px; }
        .border { border: 1px solid black; }
        .badge { padding: 5px; background-color: green; color: white; border-radius: 5px; }
        hr { border: 0; border-top: 1px solid black; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h4 class="text-center">Struk Pembayaran</h4>
        <hr>

        {{-- Kode Transaksi & Waktu --}}
        <div class="text-start"> #Pesanan | <strong> {{ $transaction->kode_transaction }}</strong> 
            <span class="text-end" style="float: right;">
                <small>{{ date('d-m-Y H:i:s', strtotime($transaction->created_at)) }}</small>
            </span>
        </div>
        <hr>

        <h5 class="text-start">Informasi Pemesanan</h5>
        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ $transaction->customer_name }}</strong></td>
            </tr>
            <tr>
                <td>No. WhatsApp</td>
                <td>:</td>
                <td><strong>{{ $transaction->customer_whatsapp }}</strong></td>
            </tr>
            <tr>
                <td>Nomor Meja</td>
                <td>:</td>
                <td><strong>{{ $transaction->table_number }}</strong></td>
            </tr>
        </table>
        <hr>

        <h5 class="text-start">Detail Pesanan</h5>
        <table class="border">
            <thead>
                <tr class="border">
                    <th class="border">Nama Item</th>
                    <th class="border">Jumlah</th>
                    <th class="border">Harga</th>
                </tr>
            </thead>
            {{-- <tbody>
                @foreach ($transaction->items as $item)
                <tr class="border">
                    <td class="border">{{ $item->name }}</td>
                    <td class="border">{{ $item->quantity }}x</td>
                    <td class="border">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody> --}}
        </table>
        <hr>

        <p class="text-start"><strong>Subtotal:</strong> Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</p>
        <p class="text-start"><strong>Pajak (PB1):</strong> Rp {{ number_format($transaction->tax, 0, ',', '.') }}</p>
        <h4 class="text-start"><strong>Total Bayar:</strong> <span class="text-end">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></h4>

        <hr>
        <h5 class="text-start">Metode Pembayaran</h5>
        <p><strong>{{ strtoupper($transaction->payment_method) }}</strong> - <span class="badge">{{ strtoupper($transaction->status) }}</span></p>

        <hr>
        <p class="text-center"><strong>Terima kasih atas pembayaranmu!</strong></p>
    </div>
</body>
</html>