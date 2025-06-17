<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log; // Gunakan Facade Log

class CartIconBadge extends Component
{
    public int $itemCount = 0; // Properti yang akan diupdate dan dirender
    public ?string $tableNumber;
    // selectedCategory dan search tidak diperlukan di sini kecuali jika Anda menggunakannya di cart.show
    // public ?string $selectedCategory;
    // public ?string $search;

    // Tambahkan listeners agar komponen ini bisa bereaksi terhadap event
    protected $listeners = [
        'refreshCartBadge' => 'updateItemCountFromSession', // Diterima dari CategoryFilter atau JS
        // 'cartUpdatedForTable' => 'updateItemCountFromSession', // Event yang lebih spesifik jika ada
    ];

    public function mount(?string $tableNumber) // Ambil tableNumber dari parent Livewire component
    {
        $this->tableNumber = $tableNumber;
        // Kita tidak bisa langsung baca sessionStorage di mount PHP
        // Kita akan memicu JavaScript untuk mengirim data setelah mount selesai.
         Log::info('[CartIconBadge PHP] Mounted with tableNumber: ' . ($this->tableNumber ?? 'null'));

        // Panggil ini saat komponen pertama kali dimuat atau dirender ulang oleh Livewire
        // Ini akan membaca dari session PHP yang sudah diisi oleh CategoryFilter
        $this->updateItemCountFromSession();
    }

    // Metode untuk memperbarui itemCount dari data yang ada di session (yang telah di-update oleh JS)
     public function updateItemCountFromSession()
    {
        // Gunakan tableNumber sebagai kunci unik untuk session
        $sessionKey = 'cartDataJson_' . ($this->tableNumber ?? 'default'); // Fallback ke 'default' jika tableNumber null
        $cartDataJson = session($sessionKey, '{}'); // Ambil dari session atau default kosong

        try {
            $cart = json_decode($cartDataJson, true);
            $count = 0;

            if ($this->tableNumber && isset($cart[$this->tableNumber]['items'])) {
                $count = count($cart[$this->tableNumber]['items']);
            }
            $this->itemCount = $count; // Perbarui properti publik
            // Livewire akan otomatis me-render ulang view setelah properti publik diubah
            Log::info('[CartIconBadge PHP] ItemCount updated to: ' . $this->itemCount . ' for session key: ' . $sessionKey);

        } catch (\Exception $e) {
            Log::error('[CartIconBadge PHP] Error updating cart count from session (key: ' . $sessionKey . '): ' . $e->getMessage());
            $this->itemCount = 0; // Reset jika ada error
        }
    }

    public function render()
    {
        return view('livewire.cart-icon-badge'); // Tidak perlu passing data ke view lagi karena sudah properti publik
    }
}