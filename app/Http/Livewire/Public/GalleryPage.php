<?php

namespace App\Livewire\Public;

use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class GalleryPage extends Component
{
    public string $password      = '';
    public bool   $authenticated = false;
    public bool   $wrongPassword = false;
    public bool   $rememberMe    = false;

    public function mount(): void
    {
        $galleryPass = SiteSetting::get('gallery_password');
        if (!$galleryPass) {
            $this->authenticated = true;
            return;
        }
        // Check cookie
        if (request()->cookie('gallery_auth') === md5($galleryPass)) {
            $this->authenticated = true;
        }
    }

    public function unlockGallery(): void
    {
        $galleryPass = SiteSetting::get('gallery_password');
        if (!$galleryPass || $this->password === $galleryPass) {
            $this->authenticated = true;
            $this->wrongPassword = false;
            if ($this->rememberMe && $galleryPass) {
                cookie()->queue(cookie('gallery_auth', md5($galleryPass), 60 * 24 * 30));
            }
        } else {
            $this->wrongPassword = true;
            $this->password      = '';
        }
    }

    public function render()
    {
        $images = $this->authenticated
            ? GalleryImage::where('active', true)->orderBy('sort_order')->get()
            : collect();

        return view('livewire.public.gallery-page', [
            'images'    => $images,
            'needsPass' => (bool) SiteSetting::get('gallery_password'),
        ]);
    }
}