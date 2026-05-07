<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ChangePassword extends Component
{
    public string $current_password = '';
    public string $password         = '';
    public string $password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'password'         => 'required|min:8|confirmed',
    ];

    public function save(): void
    {
        $this->validate();

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->password)]);
        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('toast', message: 'Password changed successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.change-password');
    }
}