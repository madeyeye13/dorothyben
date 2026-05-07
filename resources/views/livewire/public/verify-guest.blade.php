<div class="min-h-screen flex items-center justify-center px-6 py-16" style="background: var(--color-ivory);">
    <div class="max-w-md w-full text-center">

        @if(!$valid)
        {{-- Invalid QR --}}
        <div class="border border-red-200 bg-red-50 p-10">
            <div class="w-16 h-16 bg-red-100 flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 style="font-family: var(--font-serif); font-size: 1.5rem; color: #991b1b; margin-bottom: 0.75rem;">Invalid QR Code</h2>
            <p style="color: #b91c1c; font-size: 0.9375rem; line-height: 1.7;">
                This QR code is not valid or has been revoked. Please speak to a member of the wedding team.
            </p>
        </div>

        @else
        {{-- Valid Guest --}}
        <div class="border p-10 bg-white"
             style="{{ $used ? 'border-color: #fbbf24;' : 'border-color: var(--color-gold);' }}">

            {{-- Status Badge --}}
            @if($used)
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 text-xs uppercase tracking-widest" style="background: #fef3c7; color: #92400e;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                Already Scanned
            </div>
            @else
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 text-xs uppercase tracking-widest" style="background: rgba(201,168,76,0.1); color: var(--color-gold-dark);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Valid Entry Pass
            </div>
            @endif

            {{-- Couple Logo --}}
            <div class="w-12 h-12 mx-auto mb-5">
                <img src="{{ asset('images/D&B.png') }}" alt="D&B" class="w-full h-full object-contain" onerror="this.style.display='none'">
            </div>

            <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-muted);">Dorothy & Ben Wedding</p>
            <p class="text-xs mb-6" style="color: var(--color-muted);">{{ config('wedding.wedding_date') }}</p>

            <div class="border-t border-[var(--color-border)] pt-6">
                <h2 style="font-family: var(--font-serif); font-size: 1.75rem; color: var(--color-obsidian); margin-bottom: 0.25rem;">
                    {{ $guest->full_name }}
                </h2>
                <p class="text-sm mb-5" style="color: var(--color-muted);">{{ $guest->relationship_label }}</p>

                @if($guest->companions->count())
                <div class="text-left border border-[var(--color-border)] p-4 mb-4">
                    <p class="text-xs uppercase tracking-widest mb-3" style="color: var(--color-muted);">Plus Guest(s)</p>
                    @foreach($guest->companions as $comp)
                    <div class="flex items-center justify-between py-1.5 border-b border-[var(--color-border)] last:border-0">
                        <p class="text-sm font-medium">{{ $comp->name }}</p>
                        @if($comp->relation)<p class="text-xs" style="color: var(--color-muted);">{{ $comp->relation }}</p>@endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if($used)
                <div class="p-4 text-sm" style="background: #fef3c7; color: #92400e;">
                    <strong>Note:</strong> This QR code was already scanned at {{ $guest->qr_used_at?->format('g:i A') }}.
                    Please verify the guest's identity manually if needed.
                </div>
                @else
                <div class="p-4 text-sm" style="background: rgba(201,168,76,0.08); color: var(--color-gold-dark);">
                    ✓ Guest admitted at {{ now()->format('g:i A') }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <a href="{{ route('home') }}" class="inline-block mt-6 text-xs" style="color: var(--color-muted);">← Back to Website</a>
    </div>
</div>
