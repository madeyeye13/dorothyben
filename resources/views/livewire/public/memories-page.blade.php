<div>
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Relive the Day</p>
        <h1 class="section-title" style="color: #fff;">Our Memories</h1>
        <p style="color: rgba(255,255,255,0.5); font-size: 0.9375rem; max-width: 440px; margin: 1rem auto 0; line-height: 1.8;">
            Photos and videos shared by guests from our special day.
        </p>
    </div>

    <section class="py-16 px-6" style="background: var(--color-ivory);">
        <div class="max-w-5xl mx-auto">

            @if(!$active)
            {{-- ── Inactive ── --}}
            <div class="text-center py-20 max-w-md mx-auto">
                <div style="width:64px;height:64px;border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                    <svg class="w-7 h-7" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1rem;">Come Back on the Day!</h2>
                <p style="color: var(--color-muted); line-height: 1.9;">
                    This page will be active on <strong>{{ config('wedding.wedding_date') }}</strong>.
                    Come back and share your moments from the celebration!
                </p>
                <a href="{{ route('home') }}" class="btn-outline-gold mt-8 inline-flex">Go to Home</a>
            </div>

            @else
            {{-- ── Active ── --}}

            {{-- Upload Form (collapsible, open by default) --}}
            <div x-data="{ open: true }" class="mb-10">
                {{-- Toggle header --}}
                <button
                    @click="open = !open"
                    type="button"
                    class="w-full flex items-center justify-between px-5 py-4 border border-[var(--color-border)] bg-white hover:border-[var(--color-gold)] transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" style="color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span style="font-family:var(--font-serif);font-size:1.125rem;">Share Your Memories</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" style="color:var(--color-muted);"
                         :style="open ? 'transform:rotate(180deg)' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Collapsible body --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                >
                    <div class="border border-t-0 border-[var(--color-border)] bg-white p-6"
                         x-data="{
                            fileCount: 0,
                            previews: [],
                            triggerGallery() { this.$refs.mainInput.click(); },
                            triggerCamera()  { this.$refs.cameraInput.click(); },
                            handleFiles(fileList) {
                                this.fileCount = fileList.length;
                                this.previews  = [];
                                for (let f of fileList) {
                                    if (f.type.startsWith('image/')) {
                                        let r = new FileReader();
                                        r.onload = (e) => this.previews.push({ type: 'image', src: e.target.result, name: f.name });
                                        r.readAsDataURL(f);
                                    } else {
                                        this.previews.push({ type: 'video', src: null, name: f.name });
                                    }
                                }
                            },
                            onGalleryChange(e) {
                                if (!e.target.files.length) return;
                                this.handleFiles(e.target.files);
                            },
                            onCameraChange(e) {
                                if (!e.target.files.length) return;
                                let dt = new DataTransfer();
                                for (let f of e.target.files) dt.items.add(f);
                                this.$refs.mainInput.files = dt.files;
                                this.$refs.mainInput.dispatchEvent(new Event('change', { bubbles: true }));
                                this.handleFiles(e.target.files);
                            }
                         }"
                    >
                        @if($done)
                        <div class="text-center py-8">
                            <div class="text-3xl mb-3">📸</div>
                            <h3 style="font-family:var(--font-serif);font-size:1.25rem;margin-bottom:0.5rem;">Uploaded!</h3>
                            <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.25rem;">Thank you for sharing your memories.</p>
                            <button wire:click="$set('done', false)" class="btn-outline-gold text-xs">Upload More</button>
                        </div>
                        @else
                        <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.5rem;line-height:1.6;">
                            Upload photos or videos from the wedding. Select multiple files at once from your gallery.
                        </p>

                        <form wire:submit.prevent="saveMemories" class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Your Name (Optional)</label>
                                    <input wire:model="uploader_name" type="text" placeholder="e.g. Aisha Mohammed" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Caption (Optional)</label>
                                    <input wire:model="caption" type="text" placeholder="e.g. Best moment ever!" class="form-input">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Files *</label>

                                {{-- Single Livewire-bound input --}}
                                <input x-ref="mainInput" wire:model="files" type="file" multiple
                                       accept="image/*,video/*,.heic,.heif" class="hidden"
                                       @change="onGalleryChange($event)">

                                {{-- Camera input — NOT wire:model bound --}}
                                <input x-ref="cameraInput" type="file" accept="image/*,video/*,.heic,.heif"
                                       capture="environment" class="hidden"
                                       @change="onCameraChange($event)">

                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <button type="button" @click="triggerGallery()"
                                            class="flex flex-col items-center gap-2 py-5 px-3 border-2 border-dashed transition-colors"
                                            :class="fileCount > 0 ? 'border-[var(--color-gold)] bg-[var(--color-gold)]/5' : 'border-[var(--color-border)] hover:border-[var(--color-gold)]'">
                                        <svg class="w-6 h-6" style="color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium">Choose from Gallery</span>
                                        <span class="text-xs" style="color:var(--color-muted);">Multi-select</span>
                                    </button>
                                    <button type="button" @click="triggerCamera()"
                                            class="flex flex-col items-center gap-2 py-5 px-3 border-2 border-dashed border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors">
                                        <svg class="w-6 h-6" style="color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-xs font-medium">Take Photo / Video</span>
                                        <span class="text-xs" style="color:var(--color-muted);">Use camera</span>
                                    </button>
                                </div>

                                <div wire:loading wire:target="files" class="flex items-center gap-2 text-xs mb-2" style="color:var(--color-gold);">
                                    <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Preparing files...
                                </div>

                                {{-- Alpine instant previews --}}
                                <template x-if="previews.length > 0">
                                    <div>
                                        <p class="text-xs mb-2" style="color:var(--color-gold);">
                                            <span x-text="fileCount"></span> file(s) ready to upload
                                        </p>
                                        <div class="grid grid-cols-4 gap-1.5 mb-3">
                                            <template x-for="(p, i) in previews" :key="i">
                                                <div style="aspect-ratio:1;border:1px solid var(--color-border);overflow:hidden;">
                                                    <template x-if="p.type === 'image'">
                                                        <img :src="p.src" style="width:100%;height:100%;object-fit:cover;">
                                                    </template>
                                                    <template x-if="p.type === 'video'">
                                                        <div style="width:100%;height:100%;background:#f5f5f5;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;padding:4px;">
                                                            <svg style="width:20px;height:20px;color:#ccc;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                            <p style="font-size:10px;color:#aaa;text-align:center;word-break:break-all;" x-text="p.name.length > 8 ? p.name.substring(0,8)+'...' : p.name"></p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <p class="text-xs" style="color:var(--color-muted);">JPG, PNG, MP4, MOV — max 50MB each. Images compressed automatically.</p>
                                @error('files')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @error('files.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div wire:loading.remove wire:target="saveMemories">
                                <button type="submit" class="btn-gold w-full text-xs py-3"
                                        x-bind:disabled="fileCount === 0"
                                        x-bind:style="fileCount === 0 ? 'opacity:0.5;cursor:not-allowed;' : ''">
                                    <span x-text="fileCount > 0 ? 'Upload ' + fileCount + ' File(s) 📸' : 'Select Files First'"></span>
                                </button>
                            </div>
                            <div wire:loading wire:target="saveMemories" class="flex items-center justify-center gap-2 py-3 text-sm" style="color:var(--color-gold);">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Processing... Keep page open.
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Memories Gallery --}}
            <div x-data="memoriesLightbox()"
                 @keydown.escape.window="close()"
                 @keydown.arrow-right.window="if(isOpen) next()"
                 @keydown.arrow-left.window="if(isOpen) prev()">

                @if($memories->count())
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs uppercase tracking-widest" style="color:var(--color-muted);">
                        {{ $memories->total() }} {{ $memories->total() === 1 ? 'memory' : 'memories' }} shared
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mb-6">
                    @foreach($memories as $index => $memory)
                    <div style="position:relative;aspect-ratio:1;overflow:hidden;cursor:pointer;border:1px solid var(--color-border);"
                         wire:key="mem-pub-{{ $memory->id }}"
                         class="group"
                         @click="open({{ $memories->firstItem() + $loop->index - 1 }})">
                        @if($memory->type === 'image')
                            <img src="{{ $memory->url }}"
                                 alt="{{ $memory->caption ?? 'Wedding memory' }}"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;"
                                 class="group-hover:scale-105"
                                 loading="lazy">
                        @else
                            <video src="{{ $memory->url }}"
                                   style="width:100%;height:100%;object-fit:cover;"
                                   muted preload="metadata"></video>
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.3);">
                                <div style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,0.9);display:flex;align-items:center;justify-content:center;">
                                    <svg style="width:20px;height:20px;margin-left:3px;color:#0D0D0D;" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @endif
                        @if($memory->uploader_name && $memory->uploader_name !== 'Anonymous')
                        <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.6),transparent);padding:8px;opacity:0;transition:opacity 0.2s;" class="group-hover:opacity-100">
                            <p style="color:#fff;font-size:11px;">📸 {{ $memory->uploader_name }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($memories->hasPages())
                <div class="flex items-center justify-center gap-2 mt-4">
                    @if($memories->onFirstPage())
                        <span style="padding:6px 14px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">← Prev</span>
                    @else
                        <button wire:click="previousPage" style="padding:6px 14px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;" class="hover:border-[var(--color-gold)] transition-colors">← Prev</button>
                    @endif

                    <span style="font-size:12px;color:var(--color-muted);">
                        Page {{ $memories->currentPage() }} of {{ $memories->lastPage() }}
                    </span>

                    @if($memories->hasMorePages())
                        <button wire:click="nextPage" style="padding:6px 14px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;" class="hover:border-[var(--color-gold)] transition-colors">Next →</button>
                    @else
                        <span style="padding:6px 14px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">Next →</span>
                    @endif
                </div>
                @endif

                @else
                <div style="text-align:center;padding:4rem 0;color:var(--color-muted);">
                    <svg style="width:48px;height:48px;margin:0 auto 1rem;opacity:0.2;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p style="font-size:14px;">No memories yet. Be the first to share!</p>
                </div>
                @endif

                {{-- Lightbox --}}
                <div x-show="isOpen" x-cloak x-transition.opacity
                     style="position:fixed;inset:0;z-index:9000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.95);"
                     @click.self="close()">

                    {{-- Top bar --}}
                    <div style="position:absolute;top:0;left:0;right:0;display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;z-index:10;">
                        <div>
                            <p style="color:#fff;font-size:14px;font-weight:500;" x-text="current !== null ? (items[current]?.uploader || '') : ''"></p>
                            <p style="color:rgba(255,255,255,0.5);font-size:12px;" x-text="current !== null ? (items[current]?.caption || '') : ''"></p>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <span style="color:rgba(255,255,255,0.5);font-size:13px;">
                                <span x-text="current + 1"></span> / <span x-text="items.length"></span>
                            </span>
                            <a :href="current !== null ? items[current]?.url : '#'"
                               :download="'memory-' + (current + 1)"
                               style="color:rgba(255,255,255,0.7);padding:8px;display:block;" title="Download">
                                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            <button @click="close()" style="color:rgba(255,255,255,0.7);padding:8px;">
                                <svg style="width:24px;height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Prev --}}
                    <button @click="prev()" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.6);padding:12px;z-index:10;" class="hover:text-white">
                        <svg style="width:36px;height:36px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    {{-- Media --}}
                    <div style="max-width:90vw;max-height:80vh;display:flex;align-items:center;justify-content:center;margin-top:60px;">
                        <template x-if="current !== null && items[current]?.type === 'image'">
                            <img :src="items[current]?.url" style="max-width:100%;max-height:75vh;object-fit:contain;">
                        </template>
                        <template x-if="current !== null && items[current]?.type === 'video'">
                            <video :src="items[current]?.url" controls autoplay style="max-width:100%;max-height:75vh;"></video>
                        </template>
                    </div>

                    {{-- Next --}}
                    <button @click="next()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.6);padding:12px;z-index:10;" class="hover:text-white">
                        <svg style="width:36px;height:36px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            @endif

        </div>
    </section>

    <script>
    function memoriesLightbox() {
        const items = @json($memoriesData);
        return {
            items,
            isOpen:  false,
            current: null,
            open(i)  { this.current = i; this.isOpen = true; document.body.style.overflow = 'hidden'; },
            close()  { this.isOpen = false; this.current = null; document.body.style.overflow = ''; },
            next()   { this.current = (this.current + 1) % this.items.length; },
            prev()   { this.current = (this.current - 1 + this.items.length) % this.items.length; },
        }
    }
    </script>
</div>