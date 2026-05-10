<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class GuestLookup extends Component
{
    public string  $search  = '';
    public ?Guest  $found   = null;
    public bool    $noResult = false;

    public function lookup(): void
    {
        $this->noResult = false;
        $this->found    = null;

        if (strlen(trim($this->search)) < 2) return;

        $this->found = Guest::with('companions')
            ->where('attending', 'yes')
            ->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->first();

        if (!$this->found) {
            $this->noResult = true;
        }
    }

    public function manualCheckIn(): void
    {
        if (!$this->found) return;

        $this->found->update(['qr_used' => true, 'qr_used_at' => now()]);
        $this->found = $this->found->fresh();
        $this->dispatch('toast', message: "{$this->found->full_name} manually checked in.", type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.guest-lookup');
    }
}