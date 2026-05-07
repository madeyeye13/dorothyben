<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required',
    ];

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('admin.dashboard');
        }
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->redirectRoute('admin.dashboard');
        } else {
            $this->addError('email', 'These credentials do not match our records.');
        }
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}