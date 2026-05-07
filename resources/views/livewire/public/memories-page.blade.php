<div>
    {{-- Header --}}
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Relive the Day</p>
        <h1 class="section-title" style="color: #fff;">Share Your Memories</h1>
    </div>

    <section class="py-20 px-6" style="background: var(--color-ivory);">
        <div class="max-w-lg mx-auto">

            @if(!$active)
            {{-- Inactive State --}}
            <div class="text-center py-16">
                <div class="w-16 h-16 border border-[var(--color-border)] flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem;">Come Back on the Day!</h2>
                <p style="color: var(--color-muted); line-height: 1.9; max-width: 360px; margin: 0 auto;">
                    This page will open on the day of the wedding — <strong>{{ config('wedding.wedding_date') }}</strong>.
                    Come back and share your moments, photos and videos from the celebration!
                </p>
                <a href="{{ route('home') }}" class="btn-outline-gold mt-8 inline-flex">Go to Home</a>
            </div>

            @elseif($done)
            {{-- Success State --}}
            <div class="text-center py-12">
                <div class="text-4xl mb-4">📸</div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem;">Memories Uploaded!</h2>
                <p style="color: var(--color-muted); line-height: 1.8;">Thank you for sharing! Your photos and videos have been saved.</p>
                <button wire:click="$set('done', false)" class="btn-outline-gold mt-8">Upload More</button>
            </div>

            @else
            {{-- Upload Form --}}
            <div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0.5rem;">Upload Your Memories</h2>
                <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 2rem; line-height: 1.7;">
                    Share photos and videos from the wedding. You can select multiple files at once.
                </p>

                <form wire:submit.prevent="upload" class="space-y-6">
                    <div>
                        <label class="form-label">Your Name (Optional)</label>
                        <input wire:model="uploader_name" type="text" placeholder="e.g. Aisha Mohammed" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Caption (Optional)</label>
                        <input wire:model="caption" type="text" placeholder="e.g. Dancing with the couple!" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Photos & Videos *</label>
                        <div class="border-2 border-dashed border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors p-8 text-center">
                            <input wire:model="files"
                                   type="file"
                                   multiple
                                   accept="image/*,video/*"
                                   capture="environment"
                                   class="hidden"
                                   id="memory-upload">
                            <label for="memory-upload" class="cursor-pointer block">
                                <svg class="w-10 h-10 mx-auto mb-3" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-medium mb-1">Tap to select photos or videos</p>
                                <p class="text-xs" style="color: var(--color-muted);">Or take a picture/video with your camera</p>
                                <p class="text-xs mt-2" style="color: var(--color-muted);">JPG, PNG, GIF, MP4, MOV — max 50MB each</p>
                            </label>
                        </div>

                        {{-- Preview --}}
                        @if(count($files))
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            @foreach($files as $file)
                            <div class="aspect-square border border-[var(--color-border)] overflow-hidden">
                                @if(str_starts_with($file->getMimeType(), 'image'))
                                <img src="{{ $file->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14"/></svg>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @error('files')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @error('files.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div wire:loading.remove>
                        <button type="submit" class="btn-gold w-full">Upload Memories 📸</button>
                    </div>
                    <div wire:loading class="text-center py-3">
                        <div class="flex items-center justify-center gap-2" style="color: var(--color-gold);">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span class="text-sm">Uploading and compressing your files...</span>
                        </div>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </section>
</div>
