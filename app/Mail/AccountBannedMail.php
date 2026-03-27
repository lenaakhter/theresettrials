<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountBannedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $hours,
        public string $reason,
        public string $banId,
        public string $appealUrl
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your account has been temporarily banned',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-banned',
        );
    }
}
