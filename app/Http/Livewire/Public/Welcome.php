<?php

namespace App\Livewire\Public;

use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Welcome extends Component
{
    public function render()
    {
        $heroImage   = SiteSetting::get('hero_image');
        $heroUrl     = $heroImage ? asset('storage/' . $heroImage) : asset('images/hero-default.jpg');
        $rsvpEnabled = SiteSetting::get('rsvp_enabled', '1') === '1';

        return view('livewire.public.welcome', [
            'heroUrl'     => $heroUrl,
            'rsvpEnabled' => $rsvpEnabled,
            'pageTitle' => 'Dorothy & Ben — ' . config('wedding.wedding_date'),
            'metaDescription' => 'Join us as Dorothy & Ben celebrate their wedding on ' . config('wedding.wedding_date') . ' in ' . config('wedding.general_location') . '. RSVP now to confirm your attendance.',
        ]);
    }
}