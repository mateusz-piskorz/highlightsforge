<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSomething extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Something',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.mail-md',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
