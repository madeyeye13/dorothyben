<?php

namespace App\Livewire\Admin;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class SiteSettings extends Component
{
    use WithFileUploads;

    public string $rsvp_enabled      = '1';
    public string $gallery_password  = '';
    public string $memories_active   = '0';
    public string $gallery_clear_pass = ''; // separate field to clear
    public $hero_image               = null;
    public string $currentHero       = '';

    public function mount(): void
    {
        $this->rsvp_enabled     = SiteSetting::get('rsvp_enabled', '1');
        $this->gallery_password = SiteSetting::get('gallery_password', '');
        $this->memories_active  = SiteSetting::get('memories_active', '0');
        $this->currentHero      = SiteSetting::get('hero_image', '');
    }

    public function saveRsvp(): void
    {
        SiteSetting::set('rsvp_enabled', $this->rsvp_enabled);
        $this->dispatch('toast', message: 'RSVP setting saved.', type: 'success');
    }

    public function saveGalleryPassword(): void
    {
        SiteSetting::set('gallery_password', $this->gallery_password);
        $this->dispatch('toast', message: 'Gallery password updated.', type: 'success');
    }

    public function clearGalleryPassword(): void
    {
        $this->gallery_password = '';
        SiteSetting::set('gallery_password', '');
        $this->dispatch('toast', message: 'Gallery password removed. Gallery is now open.', type: 'success');
    }

    public function saveMemories(): void
    {
        SiteSetting::set('memories_active', $this->memories_active);
        $this->dispatch('toast', message: 'Memories page setting saved.', type: 'success');
    }

    public function uploadHero(): void
    {
        $this->validate(['hero_image' => 'required|image|max:10240']);

        if ($this->currentHero) {
            Storage::disk('public')->delete($this->currentHero);
        }

        $img  = \Intervention\Image\Laravel\Facades\Image::read($this->hero_image->getRealPath());
        $img->scaleDown(2560, 1440);
        $path = 'hero/' . uniqid() . '.jpg';
        Storage::disk('public')->put($path, $img->toJpeg(85));

        SiteSetting::set('hero_image', $path);
        $this->currentHero = $path;
        $this->hero_image  = null;
        $this->dispatch('toast', message: 'Hero image updated.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.site-settings');
    }
}