<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use App\Models\Wish;
use App\Models\GalleryImage;
use App\Models\Memory;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function retryJob(int $id): void
    {
        try {
            DB::table('failed_jobs')->where('id', $id)->delete();
            $this->dispatch('toast', message: 'Job removed from failed queue.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Could not retry job.', type: 'error');
        }
    }

    public function retryAllJobs(): void
    {
        $count = DB::table('failed_jobs')->count();
        DB::table('failed_jobs')->truncate();
        $this->dispatch('toast', message: "Cleared {$count} failed job(s).", type: 'success');
    }

    public function render()
    {
        $stats = [
            'total_guests'     => Guest::count(),
            'attending'        => Guest::where('attending', 'yes')->count(),
            'not_attending'    => Guest::where('attending', 'no')->count(),
            'total_companions' => \App\Models\GuestCompanion::count(),
            'total_wishes'     => Wish::count(),
            'pending_wishes'   => Wish::where('approved', false)->count(),
            'gallery_count'    => GalleryImage::count(),
            'memories_count'   => Memory::count(),
        ];

        $recentGuests = Guest::latest()->take(8)->get();

        // Failed jobs
        $failedJobs = collect();
        try {
            $failedJobs = DB::table('failed_jobs')->latest('failed_at')->take(10)->get();
        } catch (\Exception $e) {
            // failed_jobs table may not exist yet
        }

        // Queue jobs pending
        $pendingJobs = 0;
        try {
            $pendingJobs = DB::table('jobs')->count();
        } catch (\Exception $e) {}

        return view('livewire.admin.dashboard', [
            'stats'        => $stats,
            'recentGuests' => $recentGuests,
            'failedJobs'   => $failedJobs,
            'pendingJobs'  => $pendingJobs,
        ]);
    }
}