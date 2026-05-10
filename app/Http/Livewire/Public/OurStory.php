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
        $accounts = BankAccount::where('active', true)->orderBy('sort_order')->get();
        $heroUrl  = SiteSetting::get('hero_image')
            ? asset('storage/' . SiteSetting::get('hero_image'))
            : asset('images/hero-default.jpg');

        return view('livewire.public.our-story', [
            'accounts' => $accounts,
            'heroUrl'  => $heroUrl,
            'pageTitle' => 'Our Story — Dorothy & Ben',
            'metaDescription' => 'Read the beautiful love story of Dorothy and Ben, from their first meeting to their engagement and wedding day.',
        ]);
    }
}