<?php

namespace App\Livewire\Public;

use App\Models\Guest;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.public')]
class VerifyGuest extends Component
{
    public ?Guest $guest      = null;
    public bool   $valid      = false;
    public bool   $used       = false;
    public bool   $showPinForm  = false;
    public string $pin          = '';
    public bool   $pinError     = false;
    public bool   $checkedInNow = false;
    public bool   $hasPin       = false;

    #[Locked]
    public string $token = '';

    public function mount(): void
    {
        $token = request()->route('token') ?? '';

        if (empty($token)) {
            $this->valid = false;
            return;
        }

        $this->token  = $token;
        $this->hasPin = !empty(SiteSetting::get('venue_pin'));

        $guest = Guest::where('qr_token', trim($token))
                      ->where('attending', 'yes')
                      ->first();

        if (!$guest) {
            $this->valid = false;
            return;
        }

        $this->valid = true;
        $this->guest = $guest;
        $this->used  = $guest->qr_used;
    }

    // Guest or staff taps "Check In This Guest"
    public function initiateCheckIn(): void
    {
        $venuePin = SiteSetting::get('venue_pin', '');

        if (!$venuePin) {
            // No PIN set — check in immediately
            $this->performCheckIn();
        } else {
            // Show PIN prompt
            $this->showPinForm = true;
        }
    }

    public function submitPin(): void
    {
        $venuePin = SiteSetting::get('venue_pin', '');

        if ($this->pin !== $venuePin) {
            $this->pinError = true;
            $this->pin      = '';
            return;
        }

        $this->pinError    = false;
        $this->showPinForm = false;
        $this->performCheckIn();
    }

    private function performCheckIn(): void
    {
        if (!$this->guest) return;

        if (!$this->guest->qr_used) {
            $this->guest->update(['qr_used' => true, 'qr_used_at' => now()]);
            $this->guest        = $this->guest->fresh();
            $this->used         = false;
            $this->checkedInNow = true;
        } else {
            $this->used = true;
        }
    }

    public function cancelPin(): void
    {
        $this->showPinForm = false;
        $this->pin         = '';
        $this->pinError    = false;
    }

    public function render()
    {
        return view('livewire.public.verify-guest');
    }
}