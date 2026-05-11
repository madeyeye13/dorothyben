<?php

namespace App\Livewire\Public;

use App\Models\BankAccount;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class OurStory extends Component
{
    public function render()
    {
        $accounts      = \App\Models\BankAccount::where('active', true)->orderBy('sort_order')->get();
        $paymentLinks  = \App\Models\PaymentLink::where('active', true)->orderBy('sort_order')->get();
        $heroUrl       = \App\Models\SiteSetting::get('hero_image')
            ? asset('storage/' . \App\Models\SiteSetting::get('hero_image'))
            : asset('images/hero-default.jpg');

        return view('livewire.public.our-story', [
            'accounts'     => $accounts,
            'paymentLinks' => $paymentLinks,
            'heroUrl'      => $heroUrl,
        ]);
    }
}