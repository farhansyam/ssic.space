<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateIssuedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Certificate $certificate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sertifikat Kamu Sudah Terbit — '.site_setting('org_name', 'SSIC Space'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.certificate-issued',
            with: ['certificate' => $this->certificate],
        );
    }
}
