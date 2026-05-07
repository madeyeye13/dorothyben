<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Guest List</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">Manage all RSVP submissions.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live.debounce.300ms="search" type="search"
               placeholder="Search by name, email or phone..."
               class="form-input flex-1" style="max-width: 360px;">
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'attending' => 'Attending', 'not_attending' => 'Not Attending'] as $val => $label)
            <button wire:click="$set('filter', '{{ $val }}')"
                    class="text-xs px-4 py-2 border transition-colors
                    {{ $filter === $val ? 'bg-[var(--color-obsidian)] text-white border-[var(--color-obsidian)]' : 'border-[var(--color-border)] hover:border-[var(--color-gold)]' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="admin-card border border-[var(--color-border)] bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr style="background: #fafaf9; border-bottom: 1px solid var(--color-border);">
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Guest</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Contact</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Status</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Relationship</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">+Guests</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">QR</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)]">
                    @forelse($guests as $guest)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <p class="text-sm font-medium">{{ $guest->full_name }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-xs">{{ $guest->email }}</p>
                            @if($guest->phone)<p class="text-xs" style="color: var(--color-muted);">{{ $guest->phone }}</p>@endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-1
                                {{ $guest->attending === 'yes' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                {{ $guest->attending === 'yes' ? '✓ Attending' : '✕ Declined' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">
                            {{ $guest->relationship_label ?: '—' }}
                        </td>
                        <td class="px-5 py-3 text-xs text-center">
                            {{ $guest->companions->count() ?: '—' }}
                        </td>
                        <td class="px-5 py-3">
                            @if($guest->qr_token)
                            <span class="text-xs {{ $guest->qr_used ? 'text-amber-600' : 'text-emerald-600' }}">
                                {{ $guest->qr_used ? '⬤ Used' : '⬤ Unused' }}
                            </span>
                            @else
                            <span class="text-xs" style="color: var(--color-muted);">N/A</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">
                            {{ $guest->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <button wire:click="confirmDelete({{ $guest->id }})"
                                    class="text-red-400 hover:text-red-600 p-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center text-sm" style="color: var(--color-muted);">
                            No guests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-[var(--color-border)]">
            {{ $guests->links() }}
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Guest?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This will permanently remove the guest and all their data including their QR code. This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="deleteGuest" class="btn-gold text-xs py-2.5 px-5 bg-red-600 hover:bg-red-700">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
