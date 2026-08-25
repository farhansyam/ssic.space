<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $itemTitle,
        public string $itemType,
        public ?string $detailUrl = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran '.$this->itemType.' Berhasil — '.site_setting('org_name', 'SSIC Space'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registration-confirmed',
        );
    }
}
