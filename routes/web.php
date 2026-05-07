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
Route::get('/', fn() => app(Welcome::class))->name('home');
Route::get('/rsvp', fn() => app(RsvpForm::class))->name('rsvp');
Route::get('/our-story', fn() => app(OurStory::class))->name('our-story');
Route::get('/wishes', fn() => app(WishesPage::class))->name('wishes');
Route::get('/gallery', fn() => app(GalleryPage::class))->name('gallery');
Route::get('/memories', fn() => app(MemoriesPage::class))->name('memories');
Route::get('/verify/{token}', fn() => app(VerifyGuest::class))->name('verify');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', fn() => app(Login::class))->name('admin.login');

Route::get('/admin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard',  fn() => app(Dashboard::class))->name('dashboard');
    Route::get('/guests',     fn() => app(GuestList::class))->name('guests');
    Route::get('/wishes',     fn() => app(WishesManager::class))->name('wishes');
    Route::get('/gallery',    fn() => app(GalleryManager::class))->name('gallery');
    Route::get('/memories',   fn() => app(MemoriesManager::class))->name('memories');
    Route::get('/accounts',   fn() => app(AccountManager::class))->name('accounts');
    Route::get('/settings',   fn() => app(SiteSettings::class))->name('settings');
    Route::get('/users',      fn() => app(UserManager::class))->name('users');
    Route::get('/profile',    fn() => app(ChangePassword::class))->name('profile');
});