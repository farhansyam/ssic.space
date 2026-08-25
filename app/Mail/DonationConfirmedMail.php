<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Donasi Kamu Terkonfirmasi — '.site_setting('org_name', 'SSIC'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donation-confirmed',
            with: ['donation' => $this->donation],
        );
    }
}
