<?php

namespace App\Jobs;

use App\Mail\CoupleRsvpNotification;
use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCoupleNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Guest $guest, public string $action = 'new') {}

    public function handle(): void
    {
        $coupleEmail = config('wedding.couple_email', 'doroegede@yahoo.com');
        Mail::to($coupleEmail)->send(new CoupleRsvpNotification($this->guest, $this->action));
    }
}