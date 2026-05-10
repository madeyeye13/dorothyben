<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class QrScanner extends Component
{
    public ?Guest $guest       = null;
    public bool   $showModal   = false;
    public bool   $checkedIn   = false;
    public bool   $alreadyUsed = false;
    public bool   $notFound    = false;
    public string $scannedToken = '';

    // PIN state
    public string $pin          = '';
    public bool   $pinError     = false;
    public bool   $pinVerified  = false; // set client-side via sessionStorage, confirmed server-side
    public bool   $showPinSetup = false;
    public bool   $hasPin       = false;

    public function mount(): void
    {
        $this->hasPin = !empty(SiteSetting::get('venue_pin', ''));
    }

    // Called from JS when a QR token is scanned
    public function processToken(string $token): void
    {
        $this->scannedToken = $token;
        $this->resetModal();

        $guest = Guest::with('companions')
            ->where('qr_token', trim($token))
            ->where('attending', 'yes')
            ->first();

        if (!$guest) {
            $this->notFound  = true;
            $this->showModal = true;
            return;
        }

        $this->guest       = $guest;
        $this->alreadyUsed = $guest->qr_used;
        $this->showModal   = true;
    }

    // Verify PIN — called once per session
    public function verifyPin(): void
    {
        $venuePin = SiteSetting::get('venue_pin', '');

        if ($this->pin !== $venuePin) {
            $this->pinError = true;
            $this->pin      = '';
            return;
        }

        $this->pinError    = false;
        $this->pinVerified = true;
        $this->pin         = '';
        $this->showPinSetup = false;
        // Tell JS to store in sessionStorage
        $this->dispatch('pinVerified');
    }

    // Check in the guest
    public function checkIn(): void
    {
        if (!$this->guest) return;

        if (!$this->guest->qr_used) {
            $this->guest->update(['qr_used' => true, 'qr_used_at' => now()]);
            $this->guest   = $this->guest->fresh();
            $this->checkedIn = true;
            $this->alreadyUsed = false;
        } else {
            $this->alreadyUsed = true;
        }
    }

    // Close modal and resume scanning
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->dispatch('resumeScanning');
    }

    private function resetModal(): void
    {
        $this->guest       = null;
        $this->checkedIn   = false;
        $this->alreadyUsed = false;
        $this->notFound    = false;
    }

    public function render()
    {
        return view('livewire.admin.qr-scanner');
    }
}