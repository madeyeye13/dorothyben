<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoupleRsvpNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Guest $guest, public string $action = 'new') {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "New RSVP — {$this->guest->full_name} ({$this->guest->attending})");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.couple-notification', with: [
            'guest'  => $this->guest,
            'action' => $this->action,
        ]);
    }
}