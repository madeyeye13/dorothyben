<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Gallery Manager</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">Upload and manage couple photos. Images are automatically compressed.</p>
        </div>
    </div>

    {{-- Upload Zone --}}
    <div class="admin-card border border-[var(--color-border)] bg-white p-6 mb-8">
        <h3 class="text-sm font-medium mb-4">Upload New Photos</h3>
        <form wire:submit.prevent="uploadImages">
            <div class="border-2 border-dashed border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors p-8 text-center mb-4">
                <input wire:model="uploads" type="file" multiple accept="image/*" class="hidden" id="gallery-upload">
                <label for="gallery-upload" class="cursor-pointer block">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm font-medium mb-1">Click to select photos</p>
                    <p class="text-xs" style="color: var(--color-muted);">JPG, PNG, WebP — max 20MB each. Auto-compressed to max 2000px.</p>
                </label>
            </div>

            @if(count($uploads))
            <div class="grid grid-cols-4 gap-2 mb-4">
                @foreach($uploads as $upload)
                <div class="aspect-square overflow-hidden border border-[var(--color-border)]">
                    <img src="{{ $upload->temporaryUrl() }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif

            @error('uploads')<p class="text-red-500 text-xs mb-3">{{ $message }}</p>@enderror
            @error('uploads.*')<p class="text-red-500 text-xs mb-3">{{ $message }}</p>@enderror

            <div wire:loading.remove wire:target="uploadImages">
                <button type="submit" class="btn-gold text-xs" {{ !count($uploads) ? 'disabled' : '' }}>
                    Upload & Compress Photos
                </button>
            </div>
            <div wire:loading wire:target="uploadImages" class="flex items-center gap-2 text-sm" style="color: var(--color-gold);">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Uploading and compressing...
            </div>
        </form>
    </div>

    {{-- Images Grid --}}
    <div class="admin-card border border-[var(--color-border)] bg-white p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium">Gallery Images ({{ $total }})</h3>
            @if($images->hasPages())
            <p class="text-xs" style="color:var(--color-muted);">Page {{ $images->currentPage() }} of {{ $images->lastPage() }}</p>
            @endif
        </div>

        @if($images->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($images as $image)
            <div class="relative group border border-[var(--color-border)]">
                <div class="aspect-square overflow-hidden">
                    <img src="{{ $image->url }}" alt="{{ $image->caption }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button wire:click="toggleActive({{ $image->id }})"
                            class="p-1.5 rounded-full text-white" style="background: rgba(255,255,255,0.2);"
                            title="{{ $image->active ? 'Hide' : 'Show' }}">
                        @if($image->active)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        @endif
                    </button>
                    <button wire:click="confirmDelete({{ $image->id }})"
                            class="p-1.5 rounded-full text-white" style="background: rgba(239,68,68,0.7);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @if(!$image->active)
                <div class="absolute top-1 left-1 bg-black/60 text-white text-xs px-1.5 py-0.5">Hidden</div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($images->hasPages())
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--color-border);">
            <p style="font-size:12px;color:var(--color-muted);">
                Showing {{ $images->firstItem() }}–{{ $images->lastItem() }} of {{ $images->total() }}
            </p>
            <div style="display:flex;gap:6px;">
                @if($images->onFirstPage())
                    <span style="padding:5px 12px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">← Prev</span>
                @else
                    <button wire:click="previousPage" style="padding:5px 12px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;">← Prev</button>
                @endif
                @if($images->hasMorePages())
                    <button wire:click="nextPage" style="padding:5px 12px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;">Next →</button>
                @else
                    <span style="padding:5px 12px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">Next →</span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-16" style="color: var(--color-muted);">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm">No photos yet. Upload some above.</p>
        </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Photo?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This photo will be permanently deleted and removed from the gallery.</p>
            <div class="flex gap-3">
                <button wire:click="deleteImage" class="btn-gold text-xs py-2.5 px-5" style="background: #dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>