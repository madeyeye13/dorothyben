<?php

namespace App\Livewire\Admin;

use App\Models\Wish;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class WishesManager extends Component
{
    use WithPagination;

    public ?int  $deleteId  = null;
    public bool  $showModal = false;

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteWish(): void
    {
        Wish::find($this->deleteId)?->delete();
        $this->showModal = false;
        $this->deleteId  = null;
        $this->dispatch('toast', message: 'Wish deleted.', type: 'success');
    }

    public function cancelDelete(): void
    {
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function toggleApproval(int $id): void
    {
        $wish = Wish::find($id);
        if ($wish) {
            $wish->update(['approved' => !$wish->approved]);
        }
    }

    public function render()
    {
        return view('livewire.admin.wishes-manager', [
            'wishes' => Wish::latest()->paginate(20),
        ]);
    }
}