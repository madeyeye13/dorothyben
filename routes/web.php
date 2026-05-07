<?php

use App\Livewire\Public\Welcome;
use App\Livewire\Public\RsvpForm;
use App\Livewire\Public\OurStory;
use App\Livewire\Public\WishesPage;
use App\Livewire\Public\GalleryPage;
use App\Livewire\Public\MemoriesPage;
use App\Livewire\Public\VerifyGuest;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\GuestList;
use App\Livewire\Admin\WishesManager;
use App\Livewire\Admin\GalleryManager;
use App\Livewire\Admin\MemoriesManager;
use App\Livewire\Admin\AccountManager;
use App\Livewire\Admin\SiteSettings;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\ChangePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', Welcome::class)->name('home');
Route::get('/rsvp', RsvpForm::class)->name('rsvp');
Route::get('/our-story', OurStory::class)->name('our-story');
Route::get('/wishes', WishesPage::class)->name('wishes');
Route::get('/gallery', GalleryPage::class)->name('gallery');
Route::get('/memories', MemoriesPage::class)->name('memories');
Route::get('/verify/{token}', VerifyGuest::class)->name('verify');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', Login::class)->name('admin.login');

Route::get('/admin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard',  Dashboard::class)->name('dashboard');
    Route::get('/guests',     GuestList::class)->name('guests');
    Route::get('/wishes',     WishesManager::class)->name('wishes');
    Route::get('/gallery',    GalleryManager::class)->name('gallery');
    Route::get('/memories',   MemoriesManager::class)->name('memories');
    Route::get('/accounts',   AccountManager::class)->name('accounts');
    Route::get('/settings',   SiteSettings::class)->name('settings');
    Route::get('/users',      UserManager::class)->name('users');
    Route::get('/profile',    ChangePassword::class)->name('profile');
});