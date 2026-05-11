<div>
    <div class="flex items-center justify-between mb-8 flex-wrap gap-3">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Payment Links</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">
                Add clickable gift links (Zelle, PayPal, Wise, CashApp etc.) shown on the gift section.
                These are completely separate from bank account numbers.
            </p>
        </div>
        <button wire:click="openForm()" class="btn-gold text-xs">+ Add Link</button>
    </div>

    {{-- Links list --}}
    <div class="space-y-3 mb-8">
        @forelse($links as $link)
        <div class="admin-card border bg-white p-5 flex items-start justify-between gap-4
            {{ $link->active ? 'border-[var(--color-border)]' : 'border-[var(--color-border)] opacity-60' }}">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    @if($link->currency_tag)
                    <span class="text-xs px-2 py-0.5 border"
                          style="border-color:var(--color-gold);color:var(--color-gold);background:rgba(201,168,76,0.06);">
                        {{ $link->currency_tag }}
                    </span>
                    @endif
                    <p class="text-sm font-medium">{{ $link->title }}</p>
                    <span class="text-xs {{ $link->active ? 'text-emerald-600' : 'text-[var(--color-muted)]' }}">
                        {{ $link->active ? '● Visible' : '● Hidden' }}
                    </span>
                </div>
                @if($link->description)
                <p class="text-xs mb-1" style="color:var(--color-muted);">{{ $link->description }}</p>
                @endif
                <a href="{{ $link->url }}" target="_blank" rel="noopener"
                   class="text-xs truncate block" style="color:var(--color-gold);max-width:360px;">
                    {{ $link->url }}
                </a>
            </div>
            <div class="flex gap-2 shrink-0 flex-wrap">
                <button wire:click="toggleActive({{ $link->id }})"
                        class="text-xs px-3 py-1.5 border border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors">
                    {{ $link->active ? 'Hide' : 'Show' }}
                </button>
                <button wire:click="openForm({{ $link->id }})"
                        class="text-xs px-3 py-1.5 border border-[var(--color-border)] hover:border-[var(--color-gold)] transition-colors">
                    Edit
                </button>
                <button wire:click="confirmDelete({{ $link->id }})"
                        class="text-xs px-3 py-1.5 border border-red-100 text-red-400 hover:border-red-300 hover:text-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-16 border border-[var(--color-border)] bg-white" style="color:var(--color-muted);">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <p class="text-sm">No payment links yet.</p>
            <p class="text-xs mt-1">Add a Zelle, PayPal, Wise or any payment link above.</p>
        </div>
        @endforelse
    </div>

    {{-- Add/Edit Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-md w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.5rem;">
                {{ $editId ? 'Edit Payment Link' : 'Add Payment Link' }}
            </h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="form-label">Title *</label>
                    <input wire:model="title" type="text"
                           placeholder='e.g. Gift us via Zelle (USD)'
                           class="form-input">
                    <p class="text-xs mt-1" style="color:var(--color-muted);">
                        This is what guests see on the button.
                    </p>
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Link URL *</label>
                    <input wire:model="url" type="url"
                           placeholder="https://paypal.me/yourname"
                           class="form-input">
                    @error('url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Description <span style="color:var(--color-muted);">(Optional)</span></label>
                    <input wire:model="description" type="text"
                           placeholder="e.g. For US &amp; UK friends"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">Currency Tag <span style="color:var(--color-muted);">(Optional — e.g. USD, GBP)</span></label>
                    <input wire:model="currency_tag" type="text"
                           placeholder="USD"
                           class="form-input" style="max-width:120px;">
                    <p class="text-xs mt-1" style="color:var(--color-muted);">
                        Just a label to help guests identify which currency the link is for.
                    </p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-gold text-xs py-2.5 px-6">Save Link</button>
                    <button type="button" wire:click="$set('showForm', false)"
                            class="btn-outline-gold text-xs py-2.5 px-6">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Link?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This payment link will be permanently removed.</p>
            <div class="flex gap-3">
                <button wire:click="deleteLink" class="btn-gold text-xs py-2.5 px-5" style="background:#dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>