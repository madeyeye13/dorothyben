<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', \App\Livewire\Public\Welcome::class)->name('home');
Route::get('/rsvp', \App\Livewire\Public\RsvpForm::class)->name('rsvp');
Route::get('/our-story', \App\Livewire\Public\OurStory::class)->name('our-story');
Route::get('/wishes', \App\Livewire\Public\WishesPage::class)->name('wishes');
Route::get('/gallery', \App\Livewire\Public\GalleryPage::class)->name('gallery');
Route::get('/memories', \App\Livewire\Public\MemoriesPage::class)->name('memories');
Route::get('/verify/{token}', \App\Livewire\Public\VerifyGuest::class)->name('verify');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', \App\Livewire\Admin\Login::class)->name('admin.login');

Route::get('/admin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Routes — Auth required
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Default redirect based on role
    Route::get('/', function () {
        $user = auth()->user();
        if ($user && $user->hasRole('scanner')
            && ! $user->hasRole('admin')
            && ! $user->hasRole('super-admin')) {
            return redirect()->route('admin.scan');
        }
        return redirect()->route('admin.dashboard');
    });

    // ── Scanner + Admin (venue staff pages) ──
    Route::get('/scan',         \App\Livewire\Admin\QrScanner::class)->name('scan');
    Route::get('/guest-lookup', \App\Livewire\Admin\GuestLookup::class)->name('guest-lookup');

    // ── Admin / Super-admin only ──
    Route::middleware(['admin.only'])->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/guests',    \App\Livewire\Admin\GuestList::class)->name('guests');
        Route::get('/wishes',    \App\Livewire\Admin\WishesManager::class)->name('wishes');
        Route::get('/gallery',   \App\Livewire\Admin\GalleryManager::class)->name('gallery');
        Route::get('/memories',  \App\Livewire\Admin\MemoriesManager::class)->name('memories');
        Route::get('/accounts',  \App\Livewire\Admin\AccountManager::class)->name('accounts');
        Route::get('/settings',  \App\Livewire\Admin\SiteSettings::class)->name('settings');
        Route::get('/users',     \App\Livewire\Admin\UserManager::class)->name('users');
        Route::get('/profile',   \App\Livewire\Admin\ChangePassword::class)->name('profile');

        // Guest export — plain download, no Livewire
        Route::get('/guests/export', function () {
            $guests = \App\Models\Guest::with('companions')->latest()->get();

            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="guests-' . now()->format('Y-m-d') . '.csv"',
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $callback = function () use ($guests) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'Full Name', 'Email', 'Phone', 'Attending',
                    'Relationship', 'Plus Guests', 'Plus Guest Names',
                    'Decline Reason', 'QR Token', 'QR Used', 'QR Used At',
                    'Submitted At',
                ]);
                foreach ($guests as $guest) {
                    $companions = $guest->companions
                        ->map(fn($c) => $c->name . ($c->relation ? ' (' . $c->relation . ')' : ''))
                        ->join(' | ');
                    fputcsv($handle, [
                        $guest->full_name,
                        $guest->email,
                        $guest->phone ?? '',
                        strtoupper($guest->attending),
                        $guest->relationship_label,
                        $guest->companions->count(),
                        $companions,
                        $guest->decline_reason ?? '',
                        $guest->qr_token ?? '',
                        $guest->qr_used ? 'YES' : 'NO',
                        $guest->qr_used_at?->format('d M Y g:i A') ?? '',
                        $guest->created_at->format('d M Y g:i A'),
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        })->name('guests.export');
    });
});