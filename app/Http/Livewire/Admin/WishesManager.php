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

    public string $filterTab   = 'pending'; // pending | approved | all
    public ?int   $deleteId    = null;
    public bool   $showModal   = false;
    public ?int   $replyId     = null;
    public string $replyText   = '';
    public bool   $showReply   = false;

    public function updatedFilterTab(): void { $this->resetPage(); }

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

    public function approve(int $id): void
    {
        Wish::find($id)?->update(['approved' => true]);
        $this->dispatch('toast', message: 'Wish approved and now visible to public.', type: 'success');
    }

    public function unapprove(int $id): void
    {
        Wish::find($id)?->update(['approved' => false]);
        $this->dispatch('toast', message: 'Wish hidden from public.', type: 'success');
    }

    public function openReply(int $id): void
    {
        $wish            = Wish::find($id);
        $this->replyId   = $id;
        $this->replyText = $wish?->admin_reply ?? '';
        $this->showReply = true;
    }

    public function saveReply(): void
    {
        $this->validate(['replyText' => 'required|min:2|max:500']);
        Wish::find($this->replyId)?->update([
            'admin_reply' => $this->replyText,
            'replied_at'  => now(),
        ]);
        $this->showReply = false;
        $this->replyText = '';
        $this->replyId   = null;
        $this->dispatch('toast', message: 'Reply saved.', type: 'success');
    }

    public function cancelReply(): void
    {
        $this->showReply = false;
        $this->replyText = '';
        $this->replyId   = null;
    }

    public function render()
    {
        $query = Wish::latest();
        if ($this->filterTab === 'pending')  $query->where('approved', false);
        if ($this->filterTab === 'approved') $query->where('approved', true);

        return view('livewire.admin.wishes-manager', [
            'wishes'       => $query->paginate(20),
            'pendingCount' => Wish::where('approved', false)->count(),
        ]);
    }
}