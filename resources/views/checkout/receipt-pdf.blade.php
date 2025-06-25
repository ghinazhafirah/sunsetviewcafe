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
            margin: 10px 0;
        }

        /* Styles untuk header, disesuaikan untuk kompatibilitas email */
        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: top;
            /* Sejajarkan konten ke atas */
            padding: 0;
            /* Hapus padding default untuk kontrol yang lebih baik */
        }

        .header-logo {
            width: 40px;
            /* Lebar tetap untuk kolom logo */
            text-align: center;
        }

        .header-logo img {
            height: 38px;
            width: 38px;
            /* Pertahankan rasio aspek atau atur ukuran tetap */
            object-fit: contain;
            border-radius: 4px;
            display: block;
            /* Penting untuk mencegah ruang ekstra di bawah gambar */
            margin: 0 auto 2px auto;
            /* Pusatkan gambar */
        }

        .header-text-cell {
            text-align: center;
            /* Pusatkan teks di dalam selnya */
        }

        .cafe-name {
            font-size: 11px;
            font-weight: bold;
            color: #222;
        }

        .small-text {
            font-size: 9px;
            color: #555;
            line-height: 1.3;
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
        <table class="header-table">
            <!-- <tr>
                <td class="header-logo">
                   <img src="{{ public_path('img/logocafecrop.png') }}" alt="Logo Cafe" style="height: 38px; margin-bottom: 2px;">
                </td>
            </tr> -->
            <tr>
                <td class="header-text-cell">
                    <span class="cafe-name">SUNSET VIEW CAFE</span><br>
                    <span class="small-text">Jl. Candi Sari No.17, Kec. Candisari, Kota Semarang</span><br>
                    <span class="small-text">Web: sunsetviewcafe.com | Tlp: 0821-3603-1881</span><br>
                    <span class="small-text">Kode Pos: 50257</span>
                </td>
            </tr>
        </table>

        <hr>

        <h4 class="text-center">Setruk Pembayaran</h4>
        <hr>

        {{-- Kode Transaksi & Waktu --}}
        <table style="width: 100%;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>#Pesanan</strong>
                    <span style="float: right">
                        <strong>{{ $order->order_id }}</strong>
                    </span>
                </div>
                <div>
                    <small>{{ date('d-m-Y H:i:s', strtotime($order->created_at)) }}</small>
                </div>
            </div>

        </table>
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
                @foreach ($orderItems as $item)
                <tr>
                    <td class="border">{{ $item->post->title ?? 'Menu Tidak Ditemukan' }}</td>
                    <td class="border text-center">{{ $item->quantity }}x</td>
                    <td class="border text-end">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <!-- @if (strtolower($order->payment_method) !== 'cash')
        <table style="width: 100%;">
            <tr>
                <td class="text-start"><strong>Subtotal:</strong></td>
                <td class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Pajak (PB1):</strong></td>
                <td class="text-end">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
        </table>
        @endif -->

        <p class="text-start"><strong>Total Bayar:</strong>
            <span style="float: right">Rp
                {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </p>
        <hr>

        <p class="text-start">Metode Pembayaran</p>
        <p><strong>{{ strtoupper($order->payment_method) }}</strong> - <span class="badge">{{ strtoupper($order->status) }}</span></p>
        <hr>
        <p class="text-center"><strong>Terima kasih atas pembayaranmu!</strong></p>
    </div>
</body>

</html>