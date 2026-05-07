<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use App\Models\Wish;
use App\Models\GalleryImage;
use App\Models\Memory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_guests'     => Guest::count(),
            'attending'        => Guest::where('attending', 'yes')->count(),
            'not_attending'    => Guest::where('attending', 'no')->count(),
            'total_companions' => \App\Models\GuestCompanion::count(),
            'total_wishes'     => Wish::count(),
            'gallery_count'    => GalleryImage::count(),
            'memories_count'   => Memory::count(),
        ];

        $recentGuests = Guest::latest()->take(10)->get();

        return view('livewire.admin.dashboard', [
            'stats'        => $stats,
            'recentGuests' => $recentGuests,
        ]);
    }
}