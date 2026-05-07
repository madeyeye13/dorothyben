<?php

namespace App\Livewire\Admin;

use App\Models\BankAccount;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AccountManager extends Component
{
    public bool   $showForm      = false;
    public ?int   $editId        = null;
    public string $currency      = 'NGN';
    public string $bank_name     = '';
    public string $account_name  = '';
    public string $account_number = '';
    public string $sort_code     = '';
    public string $routing_number = '';
    public ?int   $deleteId      = null;
    public bool   $showModal     = false;

    protected function rules(): array
    {
        return [
            'currency'       => 'required|in:NGN,USD',
            'bank_name'      => 'required|max:120',
            'account_name'   => 'required|max:120',
            'account_number' => 'required|max:50',
            'sort_code'      => 'nullable|max:20',
            'routing_number' => 'nullable|max:20',
        ];
    }

    public function openForm(?int $id = null): void
    {
        $this->editId = $id;
        if ($id) {
            $acc = BankAccount::findOrFail($id);
            $this->currency       = $acc->currency;
            $this->bank_name      = $acc->bank_name;
            $this->account_name   = $acc->account_name;
            $this->account_number = $acc->account_number;
            $this->sort_code      = $acc->sort_code ?? '';
            $this->routing_number = $acc->routing_number ?? '';
        } else {
            $this->reset(['currency', 'bank_name', 'account_name', 'account_number', 'sort_code', 'routing_number']);
            $this->currency = 'NGN';
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'currency'       => $this->currency,
            'bank_name'      => $this->bank_name,
            'account_name'   => $this->account_name,
            'account_number' => $this->account_number,
            'sort_code'      => $this->sort_code ?: null,
            'routing_number' => $this->routing_number ?: null,
        ];
        if ($this->editId) {
            BankAccount::findOrFail($this->editId)->update($data);
            $msg = 'Account updated.';
        } else {
            BankAccount::create($data + ['sort_order' => BankAccount::max('sort_order') + 1]);
            $msg = 'Account added.';
        }
        $this->showForm = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteAccount(): void
    {
        BankAccount::find($this->deleteId)?->delete();
        $this->showModal = false;
        $this->deleteId  = null;
        $this->dispatch('toast', message: 'Account deleted.', type: 'success');
    }

    public function cancelDelete(): void
    {
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function render()
    {
        return view('livewire.admin.account-manager', [
            'accounts' => BankAccount::orderBy('sort_order')->get(),
        ]);
    }
}