<div>
    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Wishes</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Moderate and manage public wishes from well-wishers.</p>
    </div>

    <div class="admin-card border border-[var(--color-border)] bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr style="background: #fafaf9; border-bottom: 1px solid var(--color-border);">
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Name</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Message</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Status</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)]">
                    @forelse($wishes as $wish)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium">{{ $wish->name }}</td>
                        <td class="px-5 py-3 text-sm max-w-sm" style="color: var(--color-muted);">
                            {{ Str::limit($wish->message, 100) }}
                        </td>
                        <td class="px-5 py-3">
                            <button wire:click="toggleApproval({{ $wish->id }})" class="text-xs px-2 py-1 transition-colors
                                {{ $wish->approved ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-red-50 text-red-600 hover:bg-red-100' }}">
                                {{ $wish->approved ? '✓ Visible' : '✕ Hidden' }}
                            </button>
                        </td>
                        <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">{{ $wish->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <button wire:click="confirmDelete({{ $wish->id }})" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-sm" style="color: var(--color-muted);">No wishes yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-[var(--color-border)]">{{ $wishes->links() }}</div>
    </div>

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
