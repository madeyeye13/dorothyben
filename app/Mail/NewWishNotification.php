<?php

namespace App\Mail;

use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewWishNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Wish $wish) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "New Wish from {$this->wish->name} — Dorothy & Ben");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-wish', with: ['wish' => $this->wish]);
    }
}