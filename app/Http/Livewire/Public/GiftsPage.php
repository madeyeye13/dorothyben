<?php

namespace App\Livewire\Public;

use App\Models\BankAccount;
use App\Models\PaymentLink;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class GiftsPage extends Component
{
    public function render()
    {
        return view('livewire.public.gifts-page', [
            'accounts'     => BankAccount::where('active', true)->orderBy('sort_order')->get(),
            'paymentLinks' => PaymentLink::where('active', true)->orderBy('sort_order')->get(),
        ]);
    }
}