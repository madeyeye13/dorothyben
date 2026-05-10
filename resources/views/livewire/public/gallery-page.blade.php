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
                <div style="width:56px;height:56px;border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                    <svg class="w-6 h-6" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 0.75rem;">Gallery is Private</h2>
                <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 2rem;">Enter the password to view our photos.</p>

                <form wire:submit.prevent="unlockGallery" class="space-y-4 text-left">
                    <div>
                        <label class="form-label">Password</label>
                        <input wire:model="password" type="password" placeholder="Enter gallery password"
                               class="form-input" style="{{ $wrongPassword ? 'border-color:#ef4444;' : '' }}">
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
                    <a href="https://wa.me/{{ config('wedding.whatsapp_gallery') }}?text=Hello!%20I%20would%20like%20to%20request%20the%20gallery%20password."
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 mt-2 text-sm" style="color: var(--color-gold);">
                        Request Password on WhatsApp
                    </a>
                </div>
            </div>

            @else
            {{-- Gallery Grid + Lightbox --}}
            <div x-data="galleryLightbox()"
                 @keydown.escape.window="if(isOpen) close()"
                 @keydown.arrow-right.window="if(isOpen) next()"
                 @keydown.arrow-left.window="if(isOpen) prev()">

                @if($images->count())

                {{-- Count + page info --}}
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs uppercase tracking-widest" style="color:var(--color-muted);">
                        {{ $images->total() }} {{ $images->total() === 1 ? 'photo' : 'photos' }}
                    </p>
                    @if($images->hasPages())
                    <p class="text-xs" style="color:var(--color-muted);">
                        Page {{ $images->currentPage() }} of {{ $images->lastPage() }}
                    </p>
                    @endif
                </div>

                {{-- Grid --}}
                <div class="gallery-grid">
                    @foreach($images as $index => $image)
                    <div class="relative overflow-hidden cursor-pointer group"
                         style="aspect-ratio:1;"
                         wire:key="gimg-{{ $image->id }}"
                         @click="open({{ $loop->index }})">
                        <img src="{{ $image->url }}"
                             alt="{{ $image->caption ?? 'Wedding photo ' . ($loop->index + 1) }}"
                             style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;"
                             class="group-hover:scale-105"
                             loading="lazy">
                        <div style="position:absolute;inset:0;background:rgba(0,0,0,0);transition:background 0.2s;"
                             class="group-hover:bg-black/20 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </div>
                        @if(!$image->active)
                        <div style="position:absolute;top:4px;left:4px;background:rgba(0,0,0,0.6);color:#fff;font-size:10px;padding:2px 6px;">Hidden</div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($images->hasPages())
                <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:2rem;flex-wrap:wrap;">
                    @if($images->onFirstPage())
                        <span style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">← Prev</span>
                    @else
                        <button wire:click="previousPage" style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;" onmouseover="this.style.borderColor='var(--color-gold)'" onmouseout="this.style.borderColor='var(--color-border)'">← Prev</button>
                    @endif

                    @foreach($images->getUrlRange(1, $images->lastPage()) as $page => $url)
                        @if($page == $images->currentPage())
                            <span style="padding:6px 12px;border:1px solid var(--color-obsidian);background:var(--color-obsidian);color:#fff;font-size:12px;">{{ $page }}</span>
                        @elseif(abs($page - $images->currentPage()) <= 2)
                            <button wire:click="gotoPage({{ $page }})" style="padding:6px 12px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;" onmouseover="this.style.borderColor='var(--color-gold)'" onmouseout="this.style.borderColor='var(--color-border)'">{{ $page }}</button>
                        @endif
                    @endforeach

                    @if($images->hasMorePages())
                        <button wire:click="nextPage" style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;" onmouseover="this.style.borderColor='var(--color-gold)'" onmouseout="this.style.borderColor='var(--color-border)'">Next →</button>
                    @else
                        <span style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">Next →</span>
                    @endif
                </div>
                @endif

                @else
                <div class="text-center py-24" style="color: var(--color-muted);">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">Gallery photos will be added soon.</p>
                </div>
                @endif

                {{-- Lightbox --}}
                <div x-show="isOpen" x-cloak x-transition.opacity
                     style="position:fixed;inset:0;z-index:9000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.93);"
                     @click.self="close()">

                    {{-- Top controls --}}
                    <div style="position:absolute;top:0;left:0;right:0;display:flex;align-items:center;justify-content:flex-end;padding:12px 16px;gap:8px;z-index:10;">
                        {{-- Zoom out --}}
                        <button @click="zoomOut()" style="color:rgba(255,255,255,0.6);padding:8px;background:rgba(0,0,0,0.3);border:none;cursor:pointer;border-radius:50%;">
                            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                        </button>
                        {{-- Counter --}}
                        <span style="color:rgba(255,255,255,0.6);font-size:13px;padding:4px 12px;background:rgba(0,0,0,0.3);">
                            <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                        </span>
                        {{-- Zoom in --}}
                        <button @click="zoomIn()" style="color:rgba(255,255,255,0.6);padding:8px;background:rgba(0,0,0,0.3);border:none;cursor:pointer;border-radius:50%;">
                            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </button>
                        {{-- Close --}}
                        <button @click="close()" style="color:rgba(255,255,255,0.7);padding:8px;background:none;border:none;cursor:pointer;">
                            <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Prev --}}
                    <button @click="prev()" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.7);padding:12px;background:none;border:none;cursor:pointer;z-index:10;">
                        <svg style="width:36px;height:36px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    {{-- Image --}}
                    <div style="max-width:90vw;max-height:85vh;display:flex;align-items:center;justify-content:center;padding:60px 80px 40px;">
                        <img :src="images[current]?.url"
                             :alt="images[current]?.caption"
                             :style="'max-width:100%;max-height:80vh;object-fit:contain;transform:scale(' + zoomLevel + ');transition:transform 0.2s;'">
                    </div>

                    {{-- Next --}}
                    <button @click="next()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.7);padding:12px;background:none;border:none;cursor:pointer;z-index:10;">
                        <svg style="width:36px;height:36px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    {{-- Caption --}}
                    <div style="position:absolute;bottom:12px;left:0;right:0;text-align:center;">
                        <p x-show="images[current]?.caption" x-text="images[current]?.caption"
                           style="color:rgba(255,255,255,0.6);font-size:13px;"></p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>

    <script>
    function galleryLightbox() {
        const images = @json($imagesData);
        return {
            images,
            isOpen:    false,
            current:   0,
            zoomLevel: 1,
            open(i)    { this.current = i; this.isOpen = true; this.zoomLevel = 1; document.body.style.overflow = 'hidden'; },
            close()    { this.isOpen = false; document.body.style.overflow = ''; },
            next()     { this.current = (this.current + 1) % this.images.length; this.zoomLevel = 1; },
            prev()     { this.current = (this.current - 1 + this.images.length) % this.images.length; this.zoomLevel = 1; },
            zoomIn()   { if (this.zoomLevel < 3) this.zoomLevel = Math.round((this.zoomLevel + 0.25) * 100) / 100; },
            zoomOut()  { if (this.zoomLevel > 0.5) this.zoomLevel = Math.round((this.zoomLevel - 0.25) * 100) / 100; },
        }
    }
    </script>
</div>