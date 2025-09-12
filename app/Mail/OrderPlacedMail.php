<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order,$orderType = 'normal')
    {
        $this->order = $order;
        $this->type = $orderType;
    }

    public function build()
    {
        $view = match ($this->type) {
            'recurring' => 'emails.recurring-order-placed',
            default => 'emails.order-placed', // Fallback
        };

        return $this->subject('Your Order Has Been Placed')
            ->view($view)
            ->with([
                'order' => $this->order,
                'invoice_number' => $this->order->invoice->invoice_number ?? null,
            ]);
    }
}

