<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;

class CheckoutStatus extends Component
{
    public $transaction;
    public $showReceipt = false; // ✅ Tambahkan variabel ini agar bisa diakses di Blade

    protected $listeners = ['paymentUpdated' => 'refreshStatus'];

    public function mount($transactionId)
    {
        $this->transaction = Transaction::find($transactionId);
    }

    public function refreshStatus($id)
    {
        // dd("Event Diterima dengan ID:", $id); // Debugging

        if ($this->transaction->id == $id) {
            $this->transaction = Transaction::find($id);
        }
    }

    public function showReceipt()
    {
        logger('✅ Tombol STRUK ANDA diklik!');
        $this->showReceipt = true; // Ketika tombol diklik, tampilkan struk
    }

    // public function render()
    // {
    //     return view('livewire.checkout-status', ['transaction' => $this->transaction]);
    // }

    public function render()
    {
        return view('livewire.checkout-status', [
            'transaction' => $this->transaction,
            'showReceipt' => $this->showReceipt, // ✅ Kirim ke Blade agar tidak undefined
        ]);
    }
}
