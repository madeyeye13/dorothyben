<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class UserManager extends Component
{
    public bool   $showForm  = false;
    public ?int   $editId    = null;
    public string $name      = '';
    public string $email     = '';
    public string $password  = '';
    public string $password_confirmation = '';
    public ?int   $deleteId  = null;
    public bool   $showModal = false;

    protected function rules(): array
    {
        $pwdRule = $this->editId ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed';
        return [
            'name'     => 'required|min:2|max:100',
            'email'    => 'required|email|unique:users,email,' . ($this->editId ?? 'NULL'),
            'password' => $pwdRule,
        ];
    }

    public function openForm(?int $id = null): void
    {
        $this->editId = $id;
        if ($id) {
            $user         = User::findOrFail($id);
            $this->name   = $user->name;
            $this->email  = $user->email;
            $this->password = '';
            $this->password_confirmation = '';
        } else {
            $this->reset(['name', 'email', 'password', 'password_confirmation']);
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        if ($this->editId) {
            $data = ['name' => $this->name, 'email' => $this->email];
            if ($this->password) $data['password'] = Hash::make($this->password);
            User::findOrFail($this->editId)->update($data);
            $msg = 'User updated.';
        } else {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole('admin');
            $msg = 'Admin user created.';
        }
        $this->showForm = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteUser(): void
    {
        if ($this->deleteId) {
            $user = User::find($this->deleteId);
            if ($user && $user->email !== 'doroegede@yahoo.com') {
                $user->delete();
                $this->dispatch('toast', message: 'User deleted.', type: 'success');
            } else {
                $this->dispatch('toast', message: 'Cannot delete primary admin.', type: 'error');
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
        return view('livewire.admin.user-manager', [
            'users' => User::all(),
        ]);
    }
}