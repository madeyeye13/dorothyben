<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class GuestList extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $filter    = 'all'; // all, attending, not_attending
    public ?int   $deleteId  = null;
    public bool   $showModal = false;

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilter(): void { $this->resetPage(); }

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteGuest(): void
    {
        if ($this->deleteId) {
            $guest = Guest::find($this->deleteId);
            if ($guest) {
                $guest->companions()->delete();
                $guest->delete();
                $this->dispatch('toast', message: 'Guest deleted.', type: 'success');
            }
        }
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function cancelDelete(): void
    {
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function render()
    {
        $query = Guest::with('companions')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }

        if ($this->filter === 'attending')     $query->where('attending', 'yes');
        if ($this->filter === 'not_attending') $query->where('attending', 'no');

        return view('livewire.admin.guest-list', [
            'guests' => $query->paginate(20),
        ]);
    }
}