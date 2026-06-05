<?php

namespace App\Mail;

use App\Models\Rsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RsvpAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Rsvp $rsvp)
    {
    }

    public function envelope(): Envelope
    {
        $status = $this->rsvp->attending ? 'SÍ asiste' : 'NO asiste';

        return new Envelope(
            subject: "Nueva confirmación: {$this->rsvp->name} ({$status})",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.rsvp-admin',
            with: ['rsvp' => $this->rsvp],
        );
    }
}
