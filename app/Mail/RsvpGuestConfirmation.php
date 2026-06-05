<?php

namespace App\Mail;

use App\Models\Rsvp;
use App\Support\EventData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RsvpGuestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Rsvp $rsvp)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->rsvp->attending
            ? '¡Confirmación recibida! ' . config('event.tagline')
            : 'Gracias por avisarnos';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rsvp-guest',
            with: [
                'rsvp' => $this->rsvp,
                'event' => EventData::all(),
            ],
        );
    }
}
