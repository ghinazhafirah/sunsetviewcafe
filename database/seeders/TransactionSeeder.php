<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        DB::table('transactions')->insert([
            'kode_transaction' => 'BRJOBNGKPJ60S',
            'customer_name' => 'Ritha',
            'customer_whatsapp' => '0877-7998-9734',
            'table_number' => 'Indoor-G4',
            'items' => json_encode([
                ["name" => "Air Mineral", "quantity" => 2, "price" => 9091],
                ["name" => "Nasi Ayam Crispy Salted Egg + Tambah Irisan Cabai", "quantity" => 4, "price" => 69091],
                ["name" => "Nasi Ayam Crispy Bakar", "quantity" => 1, "price" => 15455],
                ["name" => "Nasi Ayam Crispy Bumbu Ireng", "quantity" => 5, "price" => 77273],
                ["name" => "Good Day Carrebian Nut + Ice", "quantity" => 6, "price" => 27273],
                ["name" => "Milo + Ice", "quantity" => 1, "price" => 5455],
                ["name" => "Air Mineral + Tidak Dingin", "quantity" => 1, "price" => 4545]
            ]),
            // 'subtotal' => 229000,
            // 'tax' => 20818,
            // 'total_price' => 229000,
            'payment_method' => 'QRIS',
            'status' => 'LUNAS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

