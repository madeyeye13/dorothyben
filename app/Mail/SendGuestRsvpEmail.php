<?php

namespace App\Jobs;

use App\Mail\GuestRsvpConfirmation;
use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendGuestRsvpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Guest $guest, public string $type = 'attending') {}

    public function handle(): void
    {
        Mail::to($this->guest->email)->send(new GuestRsvpConfirmation($this->guest, $this->type));
    }
}