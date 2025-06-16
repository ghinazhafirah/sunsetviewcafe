<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select('created_at', 'customer_name', 'customer_whatsapp', 'total_price', 'payment_method', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama',
            'Nomor WA',
            'Total',
            'Metode Pembayaran',
            'Status',
        ];
    }
}
