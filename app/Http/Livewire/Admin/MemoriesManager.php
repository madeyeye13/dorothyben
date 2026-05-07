<?php

namespace App\Livewire\Admin;

use App\Models\Memory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MemoriesManager extends Component
{
    use WithPagination;

    public ?int  $deleteId  = null;
    public bool  $showModal = false;

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteMemory(): void
    {
        $memory = Memory::find($this->deleteId);
        if ($memory) {
            Storage::disk('public')->delete($memory->path);
            $memory->delete();
            $this->dispatch('toast', message: 'Memory deleted.', type: 'success');
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
        return view('livewire.admin.memories-manager', [
            'memories' => Memory::latest()->paginate(20),
        ]);
    }
}