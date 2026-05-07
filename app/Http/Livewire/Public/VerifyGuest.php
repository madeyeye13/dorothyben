<?php

namespace App\Livewire\Public;

use App\Models\Guest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class VerifyGuest extends Component
{
    public ?Guest $guest = null;
    public bool   $valid = false;
    public bool   $used  = false;
    public string $token = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $guest = Guest::where('qr_token', $token)->where('attending', 'yes')->first();

        if (!$guest) {
            $this->valid = false;
            return;
        }

        $this->valid = true;
        $this->guest = $guest;

        if (!$guest->qr_used) {
            $guest->update(['qr_used' => true, 'qr_used_at' => now()]);
        } else {
            $this->used = true;
        }
    }

    public function render()
    {
        return view('livewire.public.verify-guest');
    }
}