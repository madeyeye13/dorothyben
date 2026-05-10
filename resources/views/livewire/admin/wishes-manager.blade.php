<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Wishes</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">Moderate, approve and reply to wishes from guests.</p>
        </div>
        @if($pendingCount > 0)
        <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 border border-amber-200">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="text-xs text-amber-700 font-medium">{{ $pendingCount }} pending approval</span>
        </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="flex gap-0 mb-6 border-b border-[var(--color-border)]">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'all' => 'All'] as $tab => $label)
        <button wire:click="$set('filterTab', '{{ $tab }}')"
                class="px-5 py-2.5 text-sm border-b-2 transition-colors -mb-px
                {{ $filterTab === $tab ? 'border-[var(--color-gold)] text-[var(--color-gold)] font-medium' : 'border-transparent text-[var(--color-muted)] hover:text-[var(--color-obsidian)]' }}">
            {{ $label }}
            @if($tab === 'pending' && $pendingCount > 0)
            <span class="ml-1.5 px-1.5 py-0.5 text-xs bg-amber-100 text-amber-700">{{ $pendingCount }}</span>
            @endif
        </button>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($wishes as $wish)
        <div class="admin-card border bg-white p-5 {{ !$wish->approved ? 'border-amber-200 bg-amber-50/30' : 'border-[var(--color-border)]' }}" wire:key="wish-{{ $wish->id }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <p class="text-sm font-medium" style="color: var(--color-gold);">{{ $wish->name }}</p>
                        <span class="text-xs px-2 py-0.5 {{ $wish->approved ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $wish->approved ? '✓ Approved' : '⏳ Pending' }}
                        </span>
                        <span class="text-xs" style="color: var(--color-muted);">{{ $wish->created_at->diffForHumans() }}</span>
                        <span class="text-xs" style="color: var(--color-muted);">· ❤️ {{ $wish->heart_count }} · 🎊 {{ $wish->congrats_count }}</span>
                    </div>
                    <p class="text-sm leading-relaxed" style="color: #3a3a3a;">"{{ $wish->message }}"</p>

                    @if($wish->admin_reply)
                    <div class="mt-3 pl-3 border-l-2 border-[var(--color-gold)]/40">
                        <p class="text-xs uppercase tracking-widest mb-1" style="color: var(--color-gold);">Your reply</p>
                        <p class="text-xs" style="color: var(--color-muted);">{{ $wish->admin_reply }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2 shrink-0">
                    @if(!$wish->approved)
                    <button wire:click="approve({{ $wish->id }})" class="text-xs px-3 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                        ✓ Approve
                    </button>
                    @else
                    <button wire:click="unapprove({{ $wish->id }})" class="text-xs px-3 py-1.5 border border-[var(--color-border)] hover:border-amber-300 text-[var(--color-muted)] transition-colors">
                        Hide
                    </button>
                    @endif
                    <button wire:click="openReply({{ $wish->id }})" class="text-xs px-3 py-1.5 border border-[var(--color-gold)]/40 hover:border-[var(--color-gold)] transition-colors" style="color: var(--color-gold);">
                        {{ $wish->admin_reply ? '✎ Edit Reply' : '↩ Reply' }}
                    </button>
                    <button wire:click="confirmDelete({{ $wish->id }})" class="text-xs px-3 py-1.5 border border-red-100 text-red-400 hover:border-red-300 hover:text-red-600 transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 border border-[var(--color-border)] bg-white" style="color: var(--color-muted);">
            <p class="text-sm">No {{ $filterTab !== 'all' ? $filterTab : '' }} wishes found.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $wishes->links() }}</div>

    {{-- Reply Modal --}}
    @if($showReply)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-md w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 1.25rem;">Reply to Wish</h3>
            <textarea wire:model="replyText" rows="4" placeholder="Write your reply..." class="form-input resize-none mb-4"></textarea>
            @error('replyText')<p class="text-red-500 text-xs mb-3">{{ $message }}</p>@enderror
            <div class="flex gap-3">
                <button wire:click="saveReply" class="btn-gold text-xs py-2.5 px-6">Save Reply</button>
                <button wire:click="cancelReply" class="btn-outline-gold text-xs py-2.5 px-6">Cancel</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Wish?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This wish will be permanently deleted.</p>
            <div class="flex gap-3">
                <button wire:click="deleteWish" class="btn-gold text-xs py-2.5 px-5" style="background: #dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
