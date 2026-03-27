<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Feedback $feedback)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New footer feedback submitted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback-submitted',
        );
    }
}
