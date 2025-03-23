<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class TransactionTable extends Component
{
    use WithPagination;

    // protected $paginationTheme = 'bootstrap'; // Agar sesuai dengan Bootstrap
    // protected $listeners = ['transactionUpdated' => '$refresh']; // Agar Livewire refresh otomatis saat ada perubahan

    public function render()
    {
        return view('livewire.transaction-table', [
            'transactions' => Transaction::latest()->paginate(10) // Ambil data terbaru
        ]);
    }

    // public function confirmPayment($transactionId)
    // {
    //     $transaction = Transaction::find($transactionId);
    //     if ($transaction && $transaction->status == 'pending') {
    //         $transaction->update(['status' => 'paid']);
    //         session()->flash('success', 'Pembayaran berhasil dikonfirmasi!');
    //     }
    // }

    // public function deleteTransaction($transactionId)
    // {
    //     $transaction = Transaction::find($transactionId);
    //     if ($transaction) {
    //         $transaction->delete();
    //         session()->flash('success', 'Transaksi berhasil dihapus.');
    //     }
    // }
}
