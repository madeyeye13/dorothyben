<?php

namespace App\Livewire\Public;

use App\Models\Wish;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class WishesPage extends Component
{
    public string $name    = '';
    public string $message = '';
    public bool   $sent    = false;

    protected $rules = [
        'name'    => 'required|min:2|max:100',
        'message' => 'required|min:5|max:1000',
    ];

    public function submit(): void
    {
        $this->validate();
        Wish::create(['name' => $this->name, 'message' => $this->message]);
        $this->sent    = true;
        $this->name    = '';
        $this->message = '';
        $this->dispatch('toast', message: 'Your wish has been sent! 💛', type: 'success');
    }

    public function dismissSent(): void
    {
        $this->sent = false;
    }

    public function render()
    {
        $wishes = Wish::where('approved', true)->latest()->get();
        return view('livewire.public.wishes-page', ['wishes' => $wishes]);
    }
}