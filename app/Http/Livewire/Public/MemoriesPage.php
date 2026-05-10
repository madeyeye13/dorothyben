<?php

namespace App\Livewire\Public;

use App\Models\Memory;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class MemoriesPage extends Component
{
    use WithFileUploads, WithPagination;

    public string $uploader_name = '';
    public string $caption       = '';
    public $files                = null;
    public bool   $done          = false;

    protected function rules(): array
    {
        return [
            'uploader_name' => 'nullable|max:100',
            'caption'       => 'nullable|max:255',
            'files'         => 'required',
            'files.*'       => 'file|max:102400|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi',
        ];
    }

    public function saveMemories(): void
    {
        if (SiteSetting::get('memories_active', '0') !== '1') return;

        $this->validate();

        $filesToProcess = is_array($this->files) ? $this->files : [$this->files];

        foreach ($filesToProcess as $file) {
            if (!$file) continue;

            $mimeType = $file->getMimeType();
            $ext      = strtolower($file->getClientOriginalExtension());
            $type     = str_starts_with($mimeType, 'video') ? 'video' : 'image';
            $filename = $file->getClientOriginalName();

            // Reject HEIC/HEIF — browsers can't display them if conversion fails
            if (in_array($ext, ['heic', 'heif']) || in_array($mimeType, ['image/heic', 'image/heif'])) {
                try {
                    // Attempt conversion (works if server has libheif)
                    $img  = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
                    $img->scaleDown(1920, 1080);
                    $path = 'memories/' . uniqid() . '.jpg';
                    Storage::disk('public')->put($path, $img->toJpeg(75));
                    $size = Storage::disk('public')->size($path);
                } catch (\Exception $e) {
                    // Server can't convert — skip this file and notify
                    $this->dispatch('toast',
                        message: "\"$filename\" is an iPhone HEIC file that could not be converted. Please convert it to JPG first.",
                        type: 'error'
                    );
                    continue; // skip to next file
                }
            } elseif ($type === 'image') {
                try {
                    $img  = \Intervention\Image\Laravel\Facades\Image::read($file->getRealPath());
                    $img->scaleDown(1920, 1080);
                    $path = 'memories/' . uniqid() . '.jpg';
                    Storage::disk('public')->put($path, $img->toJpeg(75));
                    $size = Storage::disk('public')->size($path);
                } catch (\Exception $e) {
                    $path = $file->store('memories', 'public');
                    $size = $file->getSize();
                }
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

        $this->reset(['files', 'caption', 'uploader_name']);
        $this->resetPage();
        $this->done = true;
        $this->dispatch('toast', message: 'Memories uploaded! Thank you 💛', type: 'success');
    }

    public function render()
    {
        $active           = SiteSetting::get('memories_active', '0') === '1';
        $activationDate   = SiteSetting::get('memories_activation_date', '');

        // Auto-activate if activation date has arrived
        if (!$active && $activationDate) {
            try {
                if (now()->isSameDay(\Carbon\Carbon::parse($activationDate)) || now()->isAfter(\Carbon\Carbon::parse($activationDate))) {
                    SiteSetting::set('memories_active', '1');
                    $active = true;
                }
            } catch (\Exception $e) {}
        }

        $memoriesRaw = Memory::latest()->paginate(12);

        $memoriesData = $memoriesRaw->map(function ($m) {
            return [
                'url'      => $m->url,
                'type'     => $m->type,
                'caption'  => $m->caption ?? '',
                'uploader' => ($m->uploader_name && $m->uploader_name !== 'Anonymous') ? $m->uploader_name : '',
            ];
        })->values()->toArray();

        return view('livewire.public.memories-page', [
            'active'       => $active,
            'memories'     => $memoriesRaw,
            'memoriesData' => $memoriesData,
        ]);
    }
}