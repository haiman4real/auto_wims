<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OnboardIpNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ipAddress;
    public $organization;
    public $note;
    public $approveLink;
    public $declineLink;
    public $requestInfoFormLink;
    public $requester;

    /**
     * Create a new message instance.
     */
    public function __construct($ipAddress, $organization, $note, $approveLink, $declineLink, $requestInfoFormLink, $requester)
    {
        $this->ipAddress = $ipAddress;
        $this->organization = $organization;
        $this->note = $note;
        $this->approveLink = $approveLink;
        $this->declineLink = $declineLink;
        $this->requestInfoFormLink = $requestInfoFormLink;
        $this->requester = $requester;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Action Required: Review Onboarded IP Address {$this->ipAddress}",
        );
    }

    /**
     * Get the message content definition.
     */    public function content(): Content
    {
        return new Content(
            markdown: 'mail.onboard_ip_notification',
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
