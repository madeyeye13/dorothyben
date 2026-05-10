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
    public string $filter    = 'all';
    public ?int   $deleteId  = null;
    public bool   $showModal = false;

    // Bulk select
    public array $selected        = [];
    public bool  $selectAll       = false;
    public bool  $bulkDeleteModal = false;

    public function updatedSearch(): void { $this->resetPage(); $this->selected = []; }
    public function updatedFilter(): void { $this->resetPage(); $this->selected = []; }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            // Select all IDs on current page
            $this->selected = $this->getQuery()->paginate(20)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        $pageIds = $this->getQuery()->paginate(20)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selectAll = count($this->selected) === count($pageIds) && count($pageIds) > 0;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId       = $id;
        $this->showModal      = true;
        $this->bulkDeleteModal = false;
    }

    public function deleteGuest(): void
    {
        if ($this->deleteId) {
            $guest = Guest::find($this->deleteId);
            if ($guest) {
                $guest->companions()->delete();
                $guest->delete();
                $this->selected = array_filter($this->selected, fn($id) => $id != $this->deleteId);
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

    public function confirmBulkDelete(): void
    {
        if (count($this->selected) === 0) return;
        $this->bulkDeleteModal = true;
        $this->showModal       = false;
    }

    public function bulkDelete(): void
    {
        $guests = Guest::whereIn('id', $this->selected)->get();
        foreach ($guests as $guest) {
            $guest->companions()->delete();
            $guest->delete();
        }
        $count          = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;
        $this->bulkDeleteModal = false;
        $this->dispatch('toast', message: "{$count} guests deleted.", type: 'success');
    }

    private function getQuery()
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
        return $query;
    }

    public function render()
    {
        return view('livewire.admin.guest-list', [
            'guests' => $this->getQuery()->paginate(20),
        ]);
    }
}