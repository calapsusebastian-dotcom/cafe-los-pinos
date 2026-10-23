<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo pedido #{$this->order->id} · {$this->order->customer_name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.new-order',
            with: ['url' => route('admin.orders.show', $this->order)],
        );
    }
}
