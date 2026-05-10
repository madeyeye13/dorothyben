<?php

namespace App\Jobs;

use App\Mail\NewWishNotification;
use App\Models\Wish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewWishNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Wish $wish) {}

    public function handle(): void
    {
        Mail::to(config('wedding.couple_email'))->send(new NewWishNotification($this->wish));
    }
}