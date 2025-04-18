<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdditionalInfoRequiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $allowedIp;
    public $detailsRequested;
    public $link;

    /**
     * Create a new message instance.
     */
    public function __construct($allowedIp, $detailsRequested)
    {
        $this->allowedIp = $allowedIp;
        $this->detailsRequested = $detailsRequested;
        $this->link = route('ip.userViewRequestedInfo', ['id' => $allowedIp->id]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Additional Information Required for Onboarded IP Address',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.additional_info_required',
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
