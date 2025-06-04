<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Receipt Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .container {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px;
            font-size: 11px;
        }

        .border {
            border: 1px solid #ddd;
        }

        hr {
            border: none;
            border-top: 1px dashed #ccc;
            /* Garis putus-putus untuk tampilan struk */
            margin: 10px 0;
            /* Jarak untuk garis horizontal */
        }

        .header-info {
            display: flex;
            align-items: center;
            /* Sejajarkan item secara vertikal di tengah */
            margin-bottom: 10px;
            gap: 10px;
            /* Jarak antara logo dan teks */
        }

        .header-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .header-info img {
            height: 40px;
            width: 40px;
            object-fit: contain;
            border-radius: 4px;
            /* Tidak perlu margin: auto; di sini karena sudah diatur oleh align-items pada parent */
        }

        .header-text {
            flex: 1;
            font-size: 9px;
            line-height: 1.3;
            text-align: left;
            color: #444;
        }

        .header-text .cafe-name {
            font-size: 11px;
            font-weight: bold;
            color: #222;
        }


        .small-text {
            font-size: 9px;
            /* Ukuran font lebih kecil untuk alamat dan kontak */
            color: #555;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 50mm;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 10px;
            }

            span {
                margin: 0 0;
                padding: 0 0;
            }

            table,
            td,
            th {
                font-size: 10px;
                /* Teks tabel lebih kecil untuk cetak */
                padding: 1px 2px;
            }



            h4,
            h5,
            p {
                margin: 4px 0;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="width: 100%; text-align: center; margin-top: 6px; margin-bottom: 4px;">
            <img src="{{ public_path('img/logocafe crop.png') }}" alt="Logo Cafe"
                style="height: 38px; margin-bottom: 2px;">
        </div>
        <div class="header-info">
            <div class="header-text" style="text-align: center;">
                <span class="cafe-name">SUNSET VIEW CAFE</span><br>
                Jl. Candi Sari No.17, Kec. Candisari, Kota Semarang<br>
                Web: sunsetviewcafe.com | Tlp: 0821-3603-1881<br>
                Kode Pos: 50257
            </div>
        </div>

        <hr>

        <h4 class="text-center">Struk Pembayaran</h4>
        <hr>

        {{-- Kode Transaksi & Waktu --}}
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>#Pesanan</strong>
                <span style="float: right">
                    <strong>{{ $order->kode_transaction }}</strong>
                </span>
            </div>
            <div>
                <small>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</small>
            </div>
        </div>
        <hr>

        <p class="text-start"><strong>Informasi Pemesanan</strong></p>
        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ $order->customer_name }}</strong></td>
            </tr>
            <tr>
                <td>No. WhatsApp</td>
                <td>:</td>
                <td><strong>{{ $order->customer_whatsapp }}</strong></td>
            </tr>
            <tr>
                <td>Nomor Meja</td>
                <td>:</td>
                <td><strong>{{ $order->table_number }}</strong></td>
            </tr>
        </table>
        <hr>

        <p class="text-start"><strong>Detail Pesanan</strong></p>
        <table class="border">
            <thead>
                <tr>
                    <th class="border">Nama Item</th>
                    <th class="border">Jumlah</th>
                    <th class="border">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td class="border">{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</td>
                        <td class="border text-center">{{ $item->quantity }}x</td>
                        <td class="border text-end">Rp {{ number_format($item->total_menu, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        @if (strtolower($order->payment_method) !== 'cash')
            <h5 class="text-start"><strong>Subtotal:</strong>
                <span style="float: right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </h5>
            <h5 class="text-start"><strong>Pajak (PB1):</strong>
                <span style="float: right">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
            </h5>
        @endif

        <p class="text-start"><strong>Total Bayar:</strong>
            <span style="float: right">Rp
                {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </p>
        <hr>

        <p class="text-start">Metode Pembayaran</p>
        <p><strong>{{ strtoupper($order->payment_method) }}</strong> - <span
                class="badge">{{ strtoupper($order->status) }}</span></p>
        <hr>
        <p class="text-center"><strong>Terima kasih atas pembayaranmu!</strong></p>
    </div>
</body>

</html>
