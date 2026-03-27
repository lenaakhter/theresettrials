<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BanAppealSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $banId,
        public string $username,
        public string $appeal
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Ban Appeal Submission',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ban-appeal-submitted',
        );
    }
}
