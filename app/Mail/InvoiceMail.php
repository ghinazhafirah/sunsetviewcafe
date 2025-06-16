<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Payment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected Payment $payment;
    protected Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Payment $payment,
        Order $order,
    ) {
        $this->payment = $payment;
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for Order #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'checkout.receipt-pdf',
            with: [
                'order' => $this->order,
                'payment' => $this->payment,
                'orderItems' => $this->order->orderItems,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}