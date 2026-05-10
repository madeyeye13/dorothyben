<?php

namespace App\Livewire\Admin;

use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class GalleryManager extends Component
{
    use WithFileUploads, WithPagination;

    public array  $uploads   = [];
    public bool   $uploading = false;
    public ?int   $deleteId  = null;
    public bool   $showModal = false;

    protected $rules = [
        'uploads'   => 'required|array|min:1',
        'uploads.*' => 'image|max:20480|mimes:jpg,jpeg,png,gif,webp,heic,heif',
    ];

    public function uploadImages(): void
    {
        $this->validate();
        $this->uploading = true;

        foreach ($this->uploads as $upload) {
            try {
                $img  = \Intervention\Image\Laravel\Facades\Image::read($upload->getRealPath());
                $img->scaleDown(2000, 2000);
                $path = 'gallery/' . uniqid() . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $img->toJpeg(80));
            } catch (\Exception $e) {
                // HEIC fallback — store original if server can't convert
                $path = $upload->store('gallery', 'public');
            }

            GalleryImage::create([
                'filename'   => $upload->getClientOriginalName(),
                'path'       => $path,
                'sort_order' => GalleryImage::max('sort_order') + 1,
            ]);
        }

        $this->reset('uploads');
        $this->uploading = false;
        $this->resetPage();
        $this->dispatch('toast', message: 'Images uploaded and compressed.', type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId  = $id;
        $this->showModal = true;
    }

    public function deleteImage(): void
    {
        if ($this->deleteId) {
            $img = GalleryImage::find($this->deleteId);
            if ($img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
                $this->dispatch('toast', message: 'Image deleted.', type: 'success');
            }
        }
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function cancelDelete(): void
    {
        $this->showModal = false;
        $this->deleteId  = null;
    }

    public function toggleActive(int $id): void
    {
        $img = GalleryImage::find($id);
        if ($img) $img->update(['active' => !$img->active]);
    }

    public function render()
    {
        return view('livewire.admin.gallery-manager', [
            'images' => GalleryImage::orderBy('sort_order')->paginate(24),
            'total'  => GalleryImage::count(),
        ]);
    }
}