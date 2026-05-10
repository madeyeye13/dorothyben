<?php

namespace App\Livewire\Public;

use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class GalleryPage extends Component
{
    use WithPagination;

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
        // Paginated for Livewire rendering
        $images = $this->authenticated
            ? GalleryImage::where('active', true)->orderBy('sort_order')->paginate(20)
            : collect();

        // All image data for Alpine lightbox (only current page)
        $imagesData = $this->authenticated && $images instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $images->map(function ($i) {
                return ['url' => $i->url, 'caption' => $i->caption ?? ''];
              })->values()->toArray()
            : [];

        return view('livewire.public.gallery-page', [
            'images'      => $images,
            'imagesData'  => $imagesData,
            'needsPass'   => (bool) SiteSetting::get('gallery_password'),
        ]);
    }
}