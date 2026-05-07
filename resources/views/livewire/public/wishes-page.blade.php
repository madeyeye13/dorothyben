<div>
    {{-- Header --}}
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Share Your Love</p>
        <h1 class="section-title mb-3" style="color: #fff;">Wishes & Blessings</h1>
        <p style="color: rgba(255,255,255,0.5); font-size: 0.9375rem; max-width: 440px; margin: 0 auto; line-height: 1.8;">
            Leave Dorothy & Ben a message, prayer, or heartfelt wish as they begin this beautiful journey.
        </p>
    </div>

    {{-- Form --}}
    <section class="py-20 px-6" style="background: var(--color-ivory);">
        <div class="max-w-lg mx-auto">

            @if($sent)
            <div class="text-center py-12 border border-[var(--color-gold)]/30 bg-[var(--color-gold)]/5 mb-12">
                <div class="text-3xl mb-3">💛</div>
                <h3 style="font-family: var(--font-serif); font-size: 1.375rem; margin-bottom: 0.75rem;">Your wish has been sent!</h3>
                <p style="color: var(--color-muted); font-size: 0.9375rem;">Thank you for your kind words. Dorothy & Ben will treasure this.</p>
                <button wire:click="dismissSent" class="btn-outline-gold mt-6 text-xs">Leave Another Wish</button>
            </div>
            @else
            <div class="mb-12">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 2rem;">Write Your Wish</h2>
                <form wire:submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="form-label">Your Name *</label>
                        <input wire:model="name" type="text" placeholder="e.g. Amara Okonkwo" class="form-input">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Your Message *</label>
                        <textarea wire:model="message" rows="5" placeholder="Write your wish, prayer, or message here..." class="form-input resize-none"></textarea>
                        <p class="text-right text-xs mt-1" style="color: var(--color-muted);">{{ strlen($message) }}/1000</p>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-gold w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove>Send My Wish 💛</span>
                        <span wire:loading>Sending...</span>
                    </button>
                </form>
            </div>
            @endif

            {{-- Wishes Wall --}}
            @if($wishes->count())
            <div>
                <div class="gold-divider mb-10">
                    <span class="text-xs uppercase tracking-widest" style="color: var(--color-muted); white-space: nowrap;">Wishes from loved ones</span>
                </div>
                <div class="space-y-5">
                    @foreach($wishes as $wish)
                    <div class="border border-[var(--color-border)] bg-white p-6">
                        <p style="font-size: 0.9375rem; line-height: 1.8; color: #3a3a3a; margin-bottom: 1rem;">"{{ $wish->message }}"</p>
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium" style="color: var(--color-gold);">— {{ $wish->name }}</p>
                            <p class="text-xs" style="color: var(--color-muted);">{{ $wish->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>
</div>
