<div>
    <div class="mb-8">
        <h2 style="font-family:var(--font-serif);font-size:1.75rem;">Guest Lookup</h2>
        <p class="text-sm mt-1" style="color:var(--color-muted);">
            Search for a guest by name, email or phone. Use this if a guest has lost their QR code.
        </p>
    </div>

    <div class="max-w-xl">
        {{-- Search --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-6 mb-6">
            <form wire:submit.prevent="lookup" class="flex gap-3">
                <input wire:model="search" type="text"
                       placeholder="Search by name, email or phone..."
                       class="form-input flex-1">
                <button type="submit" class="btn-gold text-xs px-6" wire:loading.attr="disabled">
                    <span wire:loading.remove>Search</span>
                    <span wire:loading>...</span>
                </button>
            </form>
        </div>

        {{-- No result --}}
        @if($noResult)
        <div style="border:1px solid #fecaca;background:#fff5f5;padding:1.25rem 1.5rem;">
            <p style="color:#dc2626;font-size:0.9375rem;">No attending guest found matching "<strong>{{ $search }}</strong>".</p>
            <p style="color:#b91c1c;font-size:0.8125rem;margin-top:0.5rem;">Only confirmed attending guests are shown. Check spelling or try their email.</p>
        </div>
        @endif

        {{-- Result --}}
        @if($found)
        <div style="border:1px solid var(--color-gold);background:#fff;">
            {{-- Header --}}
            <div style="background:var(--color-obsidian);padding:12px 20px;display:flex;align-items:center;justify-content:space-between;">
                <p style="color:rgba(255,255,255,0.6);font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0;">Guest Found</p>
                <span class="text-xs px-2 py-1 {{ $found->qr_used ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ $found->qr_used ? 'Already Checked In' : 'Not Yet Checked In' }}
                </span>
            </div>

            <div style="padding:1.5rem;">
                <h3 style="font-family:var(--font-serif);font-size:1.5rem;margin-bottom:0.25rem;">{{ $found->full_name }}</h3>
                <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1rem;">{{ $found->relationship_label }}</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1.25rem;font-size:0.875rem;">
                    <div>
                        <p style="color:var(--color-muted);font-size:11px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:2px;">Email</p>
                        <p>{{ $found->email }}</p>
                    </div>
                    @if($found->phone)
                    <div>
                        <p style="color:var(--color-muted);font-size:11px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:2px;">Phone</p>
                        <p>{{ $found->phone }}</p>
                    </div>
                    @endif
                    @if($found->qr_used)
                    <div>
                        <p style="color:var(--color-muted);font-size:11px;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:2px;">Checked In At</p>
                        <p>{{ $found->qr_used_at?->format('g:i A') }}</p>
                    </div>
                    @endif
                </div>

                @if($found->companions->count())
                <div style="border:1px solid var(--color-border);padding:12px 16px;margin-bottom:1.25rem;">
                    <p style="font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:var(--color-muted);margin-bottom:8px;">Plus Guests</p>
                    @foreach($found->companions as $comp)
                    <p style="font-size:0.875rem;">{{ $comp->name }}{{ $comp->relation ? ' — ' . $comp->relation : '' }}</p>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-3 flex-wrap">
                    @if(!$found->qr_used)
                    <button wire:click="manualCheckIn" class="btn-gold text-xs">
                        ✓ Manual Check-in
                    </button>
                    @else
                    <span style="font-size:12px;color:var(--color-muted);padding:8px 0;">
                        ✓ Already checked in at {{ $found->qr_used_at?->format('g:i A') }}
                    </span>
                    @endif
                    <a href="{{ route('verify', $found->qr_token) }}?check_in=1" target="_blank"
                       class="btn-outline-gold text-xs py-2 px-4">
                        Open Verify Page ↗
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>