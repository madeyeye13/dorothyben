<?php

namespace App\Livewire\Public;

use App\Models\Memory;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.public')]
class MemoriesPage extends Component
{
    use WithFileUploads;

    public string $uploader_name = '';
    public string $caption       = '';
    public array  $files         = [];
    public bool   $uploading     = false;
    public bool   $done          = false;

    protected $rules = [
        'uploader_name'  => 'nullable|max:100',
        'caption'        => 'nullable|max:255',
        'files'          => 'required|array|min:1',
        'files.*'        => 'file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi',
    ];

    public function upload(): void
    {
        if (SiteSetting::get('memories_active', '0') !== '1') return;

        $this->validate();
        $this->uploading = true;

        foreach ($this->files as $file) {
            $type     = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
            $filename = $file->getClientOriginalName();

            if ($type === 'image') {
                // Compress image using Intervention
                $img      = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
                $img->scaleDown(1920, 1080);
                $path     = 'memories/' . uniqid() . '.jpg';
                Storage::disk('public')->put($path, $img->toJpeg(75));
                $size = Storage::disk('public')->size($path);
            } else {
                $path = $file->store('memories', 'public');
                $size = $file->getSize();
            }

            Memory::create([
                'uploader_name' => $this->uploader_name ?: 'Anonymous',
                'filename'      => $filename,
                'path'          => $path,
                'type'          => $type,
                'caption'       => $this->caption,
                'file_size'     => $size,
            ]);
        }

        $this->reset(['files', 'caption', 'uploading']);
        $this->done = true;
        $this->dispatch('toast', message: 'Memories uploaded! Thank you 💛', type: 'success');
    }

    public function render()
    {
        $active = SiteSetting::get('memories_active', '0') === '1';
        return view('livewire.public.memories-page', ['active' => $active]);
    }
}