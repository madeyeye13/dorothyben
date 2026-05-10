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
    public ?Guest $guest    = null;
    public bool   $valid    = false;
    public bool   $used     = false;
    public bool   $isVenue  = false; // true when ?check_in=1 is in URL

    // Check-in PIN flow
    public bool   $showPinForm  = false;
    public string $pin          = '';
    public bool   $pinError     = false;
    public bool   $checkedInNow = false;

    #[Locked]
    public string $token = '';

    public function mount(): void
    {
        $token = request()->route('token') ?? '';

        if (empty($token)) {
            $this->valid = false;
            return;
        }

        $this->token   = $token;
        $this->isVenue = request()->boolean('check_in');

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

        // Only auto-mark used if venue mode AND no PIN is required
        // (PIN flow is handled via submitPin)
        if ($this->isVenue) {
            $venuePin = SiteSetting::get('venue_pin');
            if (!$venuePin) {
                // No PIN set — mark immediately in venue mode
                if (!$guest->qr_used) {
                    $guest->update(['qr_used' => true, 'qr_used_at' => now()]);
                    $this->checkedInNow = true;
                }
            } else {
                // PIN required — show PIN form
                $this->showPinForm = true;
            }
        }
        // Guest scanning their own code — view only, no marking
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

        if ($this->guest && !$this->guest->qr_used) {
            $this->guest->update(['qr_used' => true, 'qr_used_at' => now()]);
            $this->guest   = $this->guest->fresh();
            $this->used    = false; // was not used before
            $this->checkedInNow = true;
        } else {
            $this->used = true; // already checked in
        }
    }

    public function render()
    {
        return view('livewire.public.verify-guest');
    }
}