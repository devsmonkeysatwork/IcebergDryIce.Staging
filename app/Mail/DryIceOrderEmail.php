<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

//implements ShouldQueue
class DryIceOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailData['subject'] ?? 'Dry Ice Orders',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dry-ice-orders',
//            text: 'emails.dry-ice-orders-text',
            with: [
                'location' => $this->emailData['location'],
                'delivery_date' => $this->emailData['delivery_date'],
                'formatted_date' => \Carbon\Carbon::parse($this->emailData['delivery_date'])->format('F j, Y'),
                'body' => $this->emailData['body'],
                'orders' => $this->emailData['orders'] ?? collect(),
                'recipient_email' => $this->emailData['recipient_email']
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
