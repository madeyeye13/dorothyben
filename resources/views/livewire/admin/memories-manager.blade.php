<div x-data="adminMemoriesLightbox()" @keydown.escape.window="close()" @keydown.arrow-right.window="next()" @keydown.arrow-left.window="prev()">

    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Guest Memories</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Photos and videos uploaded by guests. Click any to preview.</p>
    </div>

    @if($memories->count())

    {{-- Grid view --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-6">
        @foreach($memories as $index => $memory)
        <div class="relative group border border-[var(--color-border)] bg-white overflow-hidden"
             wire:key="mem-{{ $memory->id }}">

            {{-- Thumbnail --}}
            <div class="aspect-square cursor-pointer" @click="open({{ $index }})">
                @if($memory->type === 'image')
                    <img src="{{ $memory->url }}"
                         alt="{{ $memory->caption }}"
                         class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                         loading="lazy">
                @else
                    <video src="{{ $memory->url }}"
                           class="w-full h-full object-cover"
                           muted preload="metadata"></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-10 h-10 rounded-full bg-black/50 flex items-center justify-center">
                            <svg class="w-5 h-5 ml-0.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                @endif

                {{-- Hover overlay --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </div>
            </div>

            {{-- Info bar --}}
            <div class="px-2 py-1.5 border-t border-[var(--color-border)]">
                <p class="text-xs font-medium truncate">{{ $memory->uploader_name }}</p>
                <p class="text-xs" style="color:var(--color-muted);">{{ $memory->file_size_human }}</p>
            </div>

            {{-- Delete button --}}
            <button wire:click="confirmDelete({{ $memory->id }})"
                    class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-red-500/80 hover:bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endforeach
    </div>

    <div class="border-t border-[var(--color-border)] pt-4">
        {{ $memories->links() }}
    </div>

    @else
    <div class="text-center py-24 border border-[var(--color-border)] bg-white" style="color:var(--color-muted);">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-sm">No memories uploaded yet.</p>
    </div>
    @endif

    {{-- ── Lightbox ── --}}
    <div x-show="isOpen"
         x-transition.opacity
         class="fixed inset-0 z-[9000] flex items-center justify-center bg-black/95"
         style="display:none;"
         @click.self="close()">

        {{-- Top bar --}}
        <div class="absolute top-0 left-0 right-0 flex items-center justify-between px-5 py-4 z-10">
            <div>
                <p class="text-white text-sm font-medium" x-text="current !== null ? (items[current]?.uploader || 'Anonymous') : ''"></p>
                <p class="text-white/50 text-xs" x-text="current !== null ? (items[current]?.caption || '') : ''"></p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Type badge --}}
                <span class="text-xs px-2 py-1 text-white/60 border border-white/20"
                      x-text="current !== null ? items[current]?.type?.toUpperCase() : ''"></span>

                {{-- Counter --}}
                <span class="text-white/50 text-sm">
                    <span x-text="current + 1"></span> / <span x-text="items.length"></span>
                </span>

                {{-- Download --}}
                <a :href="current !== null ? items[current]?.url : '#'"
                   :download="'memory-' + (current !== null ? current + 1 : '')"
                   class="p-2 text-white/70 hover:text-white transition-colors"
                   title="Download">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>

                {{-- Close --}}
                <button @click="close()" class="p-2 text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Prev --}}
        <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 text-white/60 hover:text-white p-3 z-10 transition-colors">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </button>

        {{-- Media --}}
        <div class="max-w-5xl max-h-[80vh] flex items-center justify-center px-20 mt-14">
            <template x-if="current !== null && items[current]?.type === 'image'">
                <img :src="items[current]?.url"
                     class="max-w-full max-h-[75vh] object-contain shadow-2xl">
            </template>
            <template x-if="current !== null && items[current]?.type === 'video'">
                <video :src="items[current]?.url"
                       controls autoplay
                       class="max-w-full max-h-[75vh] shadow-2xl"></video>
            </template>
        </div>

        {{-- Next --}}
        <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/60 hover:text-white p-3 z-10 transition-colors">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    {{-- Delete confirm modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Memory?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This file will be permanently deleted from storage.</p>
            <div class="flex gap-3">
                <button wire:click="deleteMemory" class="btn-gold text-xs py-2.5 px-5" style="background:#dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif

    <script>
    function adminMemoriesLightbox() {
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