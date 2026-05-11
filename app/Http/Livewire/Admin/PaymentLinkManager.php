<?php

namespace App\Livewire\Admin;

use App\Models\PaymentLink;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PaymentLinkManager extends Component
{
    public bool   $showForm    = false;
    public ?int   $editId      = null;
    public string $title        = '';
    public string $url          = '';
    public string $description  = '';
    public string $currency_tag = '';
    public ?int   $deleteId    = null;
    public bool   $showModal   = false;

    protected function rules(): array
    {
        return [
            'title'        => 'required|max:120',
            'url'          => 'required|url|max:500',
            'description'  => 'nullable|max:200',
            'currency_tag' => 'nullable|max:10',
        ];
    }

    public function openForm(?int $id = null): void
    {
        $this->editId = $id;
        if ($id) {
            $link                = PaymentLink::findOrFail($id);
            $this->title        = $link->title;
            $this->url          = $link->url;
            $this->description  = $link->description ?? '';
            $this->currency_tag = $link->currency_tag ?? '';
        } else {
            $this->reset(['title', 'url', 'description', 'currency_tag']);
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'title'        => $this->title,
            'url'          => $this->url,
            'description'  => $this->description ?: null,
            'currency_tag' => $this->currency_tag ?: null,
        ];
        if ($this->editId) {
            PaymentLink::findOrFail($this->editId)->update($data);
            $msg = 'Payment link updated.';
        } else {
            PaymentLink::create($data + ['sort_order' => PaymentLink::max('sort_order') + 1]);
            $msg = 'Payment link added.';
        }
        $this->showForm = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $link = PaymentLink::find($id);
        if ($link) $link->update(['active' => !$link->active]);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteLink(): void
    {
        PaymentLink::find($this->deleteId)?->delete();
        $this->showModal = false;
        $this->deleteId  = null;
        $this->dispatch('toast', message: 'Payment link deleted.', type: 'success');
    }

    public function cancelDelete(): void
    {
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function render()
    {
        return view('livewire.admin.payment-link-manager', [
            'links' => PaymentLink::orderBy('sort_order')->get(),
        ]);
    }
}