<div class="min-h-screen flex items-center justify-center px-6 py-16" style="background: var(--color-ivory);">
    <div style="max-width: 420px; width: 100%;">

        @if(!$valid)
        {{-- ── Invalid QR ── --}}
        <div style="border:1px solid #fecaca;background:#fff5f5;padding:2.5rem;text-align:center;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg style="width:28px;height:28px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 style="font-family:var(--font-serif);font-size:1.375rem;color:#991b1b;margin-bottom:0.75rem;">Invalid QR Code</h2>
            <p style="color:#b91c1c;font-size:0.9375rem;line-height:1.7;">
                This QR code is not valid or has been revoked. Please speak to a member of the wedding team.
            </p>
        </div>

        @elseif($showPinForm)
        {{-- ── PIN Entry (venue staff only) ── --}}
        <div style="border:1px solid var(--color-border);background:#fff;padding:2.5rem;text-align:center;">
            <div style="width:48px;height:48px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg style="width:22px;height:22px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 style="font-family:var(--font-serif);font-size:1.375rem;margin-bottom:0.5rem;">Venue Staff Check-in</h2>
            <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.5rem;">Enter the venue PIN to check in this guest.</p>

            <div style="margin-bottom:1rem;">
                <input wire:model="pin" type="password" inputmode="numeric" placeholder="Enter PIN"
                       class="form-input text-center text-lg tracking-widest"
                       style="max-width:200px;margin:0 auto;letter-spacing:0.3em;"
                       @keydown.enter="$wire.submitPin()">
                @if($pinError)
                <p style="color:#ef4444;font-size:0.8125rem;margin-top:0.5rem;">Incorrect PIN. Please try again.</p>
                @endif
            </div>
            <button wire:click="submitPin" class="btn-gold text-xs px-8">Check In Guest</button>
        </div>

        @else
        {{-- ── Valid Guest Card ── --}}
        <div style="border:1px solid {{ $checkedInNow ? 'var(--color-gold)' : ($used ? '#fbbf24' : 'var(--color-gold)') }};background:#fff;">

            {{-- Status banner --}}
            @if($checkedInNow)
            <div style="background:var(--color-gold);padding:10px 20px;display:flex;align-items:center;gap:8px;">
                <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <p style="color:#fff;font-size:12px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;margin:0;">
                    Checked In — {{ now()->format('g:i A') }}
                </p>
            </div>
            @elseif($used && $isVenue)
            <div style="background:#fef3c7;padding:10px 20px;display:flex;align-items:center;gap:8px;">
                <svg style="width:16px;height:16px;color:#92400e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                </svg>
                <p style="color:#92400e;font-size:12px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;margin:0;">
                    Already Checked In — {{ $guest->qr_used_at?->format('g:i A') }}
                </p>
            </div>
            @else
            {{-- Guest viewing their own code --}}
            <div style="background:var(--color-obsidian);padding:10px 20px;display:flex;align-items:center;justify-content:space-between;">
                <p style="color:rgba(255,255,255,0.6);font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0;">Entry Pass</p>
                <p style="color:var(--color-gold);font-size:11px;margin:0;">{{ config('wedding.wedding_date') }}</p>
            </div>
            @endif

            {{-- Guest info --}}
            <div style="padding:2rem;text-align:center;">
                <div style="width:48px;height:48px;margin:0 auto 1rem;">
                    <img src="{{ asset('images/D&B.png') }}" alt="D&B" style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none'">
                </div>
                <p style="font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:var(--color-muted);margin:0 0 4px;">Dorothy & Ben Wedding</p>

                <h2 style="font-family:var(--font-serif);font-size:1.875rem;color:var(--color-obsidian);margin:0.75rem 0 0.25rem;">
                    {{ $guest->full_name }}
                </h2>
                <p style="font-size:0.875rem;color:var(--color-muted);margin-bottom:1.25rem;">{{ $guest->relationship_label }}</p>

                @if($guest->companions->count())
                <div style="border:1px solid var(--color-border);padding:1rem;margin-bottom:1rem;text-align:left;">
                    <p style="font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--color-muted);margin:0 0 0.5rem;">Plus Guest(s)</p>
                    @foreach($guest->companions as $comp)
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--color-border);" class="last:border-0">
                        <p style="font-size:0.875rem;font-weight:500;margin:0;">{{ $comp->name }}</p>
                        @if($comp->relation)<p style="font-size:0.75rem;color:var(--color-muted);margin:0;">{{ $comp->relation }}</p>@endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if(!$isVenue)
                {{-- Guest view: just show info, no check-in status --}}
                <div style="background:#fafaf9;border:1px solid var(--color-border);padding:12px 16px;margin-top:0.5rem;">
                    <p style="font-size:12px;color:var(--color-muted);margin:0;">
                        Present this QR code at the venue entrance on <strong>{{ config('wedding.wedding_date') }}</strong>.
                    </p>
                </div>
                @elseif($checkedInNow)
                <div style="background:rgba(201,168,76,0.06);border:1px solid rgba(201,168,76,0.3);padding:12px 16px;margin-top:0.5rem;">
                    <p style="font-size:12px;color:var(--color-gold-dark);margin:0;">✓ Guest successfully admitted</p>
                </div>
                @elseif($used)
                <div style="background:#fef3c7;border:1px solid #fde68a;padding:12px 16px;margin-top:0.5rem;">
                    <p style="font-size:12px;color:#92400e;margin:0;">
                        <strong>Note:</strong> This guest was already checked in at {{ $guest->qr_used_at?->format('g:i A') }}.
                        Verify identity manually if needed.
                    </p>
                </div>
                @endif
            </div>
        </div>

        @endif

        <a href="{{ route('home') }}" style="display:block;text-align:center;margin-top:1.5rem;font-size:12px;color:var(--color-muted);text-decoration:none;">
            ← Back to Website
        </a>
    </div>
</div>