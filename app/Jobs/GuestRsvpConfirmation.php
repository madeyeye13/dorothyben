<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestRsvpConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Guest $guest, public string $type = 'attending') {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'attending'    => "You're Coming! — Dorothy & Ben Wedding 🎉",
            'not_attending' => "We'll Miss You — Dorothy & Ben Wedding",
            'updated_attending'    => "We're Glad You'll Join Us! — Dorothy & Ben Wedding",
            'updated_not_attending' => "We'll Miss You — Dorothy & Ben Wedding",
            default        => "RSVP Confirmation — Dorothy & Ben Wedding",
        };
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.guest-rsvp', with: [
            'guest' => $this->guest,
            'type'  => $this->type,
        ]);
    }
}