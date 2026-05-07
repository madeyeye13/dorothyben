<div>
    {{-- Header --}}
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Our Moments</p>
        <h1 class="section-title" style="color: #fff;">Photo Gallery</h1>
    </div>

    <section class="py-16 px-6" style="background: var(--color-ivory);">
        <div class="max-w-7xl mx-auto">

            @if(!$authenticated)
            {{-- Password Gate --}}
            <div class="max-w-sm mx-auto text-center py-16">
                <div class="w-14 h-14 border border-[var(--color-border)] flex items-center justify-center mx-auto mb-6">
                    <svg class="w-6 h-6" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0.75rem;">Gallery is Private</h2>
                <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 2rem;">Enter the password to view our photos.</p>

                <form wire:submit.prevent="unlockGallery" class="space-y-4 text-left">
                    <div>
                        <label class="form-label">Password</label>
                        <input wire:model="password" type="password" placeholder="Enter gallery password" class="form-input"
                               style="{{ $wrongPassword ? 'border-color: #ef4444;' : '' }}">
                        @if($wrongPassword)
                        <p class="text-red-500 text-xs mt-1">Incorrect password. Please try again.</p>
                        @endif
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer text-sm" style="color: var(--color-muted);">
                        <input wire:model="rememberMe" type="checkbox" class="w-4 h-4 accent-[var(--color-gold)]">
                        Remember me on this device
                    </label>
                    <button type="submit" class="btn-gold w-full">Unlock Gallery</button>
                </form>

                <div class="mt-8 pt-8 border-t border-[var(--color-border)]">
                    <p class="text-xs" style="color: var(--color-muted);">Don't have the password?</p>
                    <a href="https://wa.me/{{ config('wedding.whatsapp_gallery') }}?text=Hello!%20I%20would%20like%20to%20request%20the%20gallery%20password%20for%20Dorothy%20%26%20Ben's%20wedding%20website."
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 mt-2 text-sm" style="color: var(--color-gold);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.554 4.118 1.525 5.847L.057 23.7a.75.75 0 00.938.936l5.884-1.473A11.949 11.949 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.891 0-3.659-.518-5.172-1.42l-.369-.22-3.831.958.975-3.801-.24-.38A9.95 9.95 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                        Request Password on WhatsApp
                    </a>
                </div>
            </div>

            @else
            {{-- Gallery Grid with Lightbox --}}
            <div
                x-data="gallery()"
                @keydown.escape.window="close()"
                @keydown.arrow-right.window="next()"
                @keydown.arrow-left.window="prev()"
            >
                @if($images->count())
                <div class="gallery-grid">
                    @foreach($images as $index => $image)
                    <div class="relative overflow-hidden cursor-pointer group aspect-square"
                         wire:key="img-{{ $image->id }}"
                         @click="open({{ $index }})">
                        <img src="{{ $image->url }}"
                             alt="{{ $image->caption ?? 'Wedding photo ' . ($index + 1) }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             loading="lazy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Lightbox --}}
                <div x-show="isOpen" x-transition.opacity class="lightbox-overlay" style="display:none;">
                    <div class="relative w-full h-full flex items-center justify-center p-4">

                        {{-- Close --}}
                        <button @click="close()" class="absolute top-4 right-4 text-white/70 hover:text-white z-10 p-2">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        {{-- Prev --}}
                        <button @click="prev()" class="absolute left-4 text-white/70 hover:text-white p-3 z-10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>

                        {{-- Image --}}
                        <div class="max-w-5xl max-h-[85vh] flex items-center justify-center">
                            <img :src="images[current]?.url" :alt="images[current]?.caption"
                                 class="max-w-full max-h-[80vh] object-contain"
                                 :style="'transform: scale(' + zoomLevel + ')'"
                                 style="transition: transform 0.2s;">
                        </div>

                        {{-- Next --}}
                        <button @click="next()" class="absolute right-4 text-white/70 hover:text-white p-3 z-10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Controls --}}
                        <div class="absolute bottom-4 flex items-center gap-4">
                            {{-- Zoom Out --}}
                            <button @click="zoomOut()" class="text-white/70 hover:text-white p-2 bg-black/40 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                            </button>
                            {{-- Counter --}}
                            <span class="text-white/60 text-sm px-3 bg-black/40 py-1 rounded">
                                <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                            </span>
                            {{-- Zoom In --}}
                            <button @click="zoomIn()" class="text-white/70 hover:text-white p-2 bg-black/40 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                @else
                <div class="text-center py-24" style="color: var(--color-muted);">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm">Gallery photos will be added soon.</p>
                </div>
                @endif
            </div>
            @endif

        </div>
    </section>

    <script>
    function gallery() {
        const imgs = @json($images->map(fn($i) => ['url' => $i->url, 'caption' => $i->caption])->values());
        return {
            images: imgs,
            isOpen: false,
            current: 0,
            zoomLevel: 1,
            open(i) { this.current = i; this.isOpen = true; this.zoomLevel = 1; document.body.style.overflow = 'hidden'; },
            close() { this.isOpen = false; document.body.style.overflow = ''; },
            next() { this.current = (this.current + 1) % this.images.length; this.zoomLevel = 1; },
            prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; this.zoomLevel = 1; },
            zoomIn() { if (this.zoomLevel < 3) this.zoomLevel = Math.round((this.zoomLevel + 0.25) * 100) / 100; },
            zoomOut() { if (this.zoomLevel > 0.5) this.zoomLevel = Math.round((this.zoomLevel - 0.25) * 100) / 100; },
        }
    }
    </script>
</div>
