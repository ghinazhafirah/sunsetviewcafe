<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;

class OrderStatus extends Component
{
    public $transaction;
    protected $listeners = ['paymentUpdated' => 'refreshStatus'];

    public function mount($transactionId)
    {
        $this->transaction = Transaction::find($transactionId);
    }

    public function refreshStatus()
    {
        $this->transaction = Transaction::find($this->transaction->id);
    }
    
    public function render()
    {
        return view('livewire.order-status');
    }
}
