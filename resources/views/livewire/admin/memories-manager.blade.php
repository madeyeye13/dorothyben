<div>
    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Guest Memories</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Photos and videos uploaded by guests from the event.</p>
    </div>

    <div class="admin-card border border-[var(--color-border)] bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full admin-table">
                <thead>
                    <tr style="background: #fafaf9; border-bottom: 1px solid var(--color-border);">
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Preview</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Uploader</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Caption</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Type</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Size</th>
                        <th class="text-left px-5 py-3 text-xs uppercase tracking-widest" style="color: var(--color-muted);">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border)]">
                    @forelse($memories as $memory)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            @if($memory->type === 'image')
                            <a href="{{ $memory->url }}" target="_blank">
                                <img src="{{ $memory->url }}" class="w-14 h-14 object-cover border border-[var(--color-border)]" loading="lazy">
                            </a>
                            @else
                            <div class="w-14 h-14 flex items-center justify-center border border-[var(--color-border)] bg-gray-50">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm">{{ $memory->uploader_name }}</td>
                        <td class="px-5 py-3 text-sm max-w-xs" style="color: var(--color-muted);">{{ $memory->caption ?: '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-0.5 {{ $memory->type === 'image' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                {{ strtoupper($memory->type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">{{ $memory->file_size_human }}</td>
                        <td class="px-5 py-3 text-xs" style="color: var(--color-muted);">{{ $memory->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <button wire:click="confirmDelete({{ $memory->id }})" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-16 text-center text-sm" style="color: var(--color-muted);">No memories uploaded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-[var(--color-border)]">{{ $memories->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="bg-white max-w-sm w-full p-8">
            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.75rem;">Delete Memory?</h3>
            <p class="text-sm mb-6" style="color: var(--color-muted);">This file will be permanently deleted.</p>
            <div class="flex gap-3">
                <button wire:click="deleteMemory" class="btn-gold text-xs py-2.5 px-5" style="background: #dc2626;">Delete</button>
                <button wire:click="cancelDelete" class="btn-outline-gold text-xs py-2.5 px-5">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
