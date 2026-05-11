<div>
    {{-- Header --}}
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Share Your Love</p>
        <h1 class="section-title mb-3" style="color: #fff;">Wishes & Blessings</h1>
        <p style="color: rgba(255,255,255,0.5); font-size: 0.9375rem; max-width: 440px; margin: 0 auto; line-height: 1.8;">
            Leave Dorothy & Ben a message, prayer, or heartfelt wish as they begin this beautiful journey.
        </p>
    </div>

    <section class="py-20 px-6" style="background: var(--color-ivory);">
        <div class="max-w-lg mx-auto">

            @if($sent)
            <div class="text-center py-12 border border-[var(--color-gold)]/30 bg-[var(--color-gold)]/5 mb-12">
                <div class="text-3xl mb-3">💛</div>
                <h3 style="font-family: var(--font-serif); font-size: 1.375rem; margin-bottom: 0.75rem;">Your wish has been sent!</h3>
                <p style="color: var(--color-muted); font-size: 0.9375rem; line-height: 1.7;">
                    Thank you! Your wish is pending a quick review and will appear here shortly.
                </p>
                <button wire:click="dismissSent" class="btn-outline-gold mt-6 text-xs">Leave Another Wish</button>
            </div>
            @else
            <div class="mb-14">
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

            @if($wishes->count())
            <div>
                <div class="gold-divider mb-10">
                    <span class="text-xs uppercase tracking-widest" style="color: var(--color-muted); white-space: nowrap;">Wishes from loved ones</span>
                </div>
                <div class="space-y-5">
                    @foreach($wishes as $wish)
                    @php
                        $userReactions = $myReactions[$wish->id] ?? collect();
                        $hasHeart    = $userReactions->where('reaction_type', 'heart')->count() > 0;
                        $hasCongrats = $userReactions->where('reaction_type', 'congrats')->count() > 0;
                    @endphp
                    <div class="border border-[var(--color-border)] bg-white p-6" wire:key="wish-{{ $wish->id }}">
                        <p style="font-size: 0.9375rem; line-height: 1.8; color: #3a3a3a; margin-bottom: 1rem;">"{{ $wish->message }}"</p>

                        @if($wish->admin_reply)
                        <div class="mt-3 mb-4 pl-4 border-l-2 border-[var(--color-gold)]/40">
                            <p class="text-xs uppercase tracking-widest mb-1" style="color: var(--color-gold);">Dorothy & Ben replied</p>
                            <p class="text-sm" style="color: #3a3a3a; line-height: 1.7;">{{ $wish->admin_reply }}</p>
                        </div>
                        @endif

                        <div class="flex items-center justify-between flex-wrap gap-3 mt-4">
                            <p class="text-sm font-medium" style="color: var(--color-gold);">— {{ $wish->name }}</p>
                            <div class="flex items-center gap-2">
                                <button wire:click="react({{ $wish->id }}, 'heart')"
                                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 border transition-all
                                        {{ $hasHeart ? 'border-red-300 bg-red-50 text-red-500' : 'border-[var(--color-border)] hover:border-red-200 text-[var(--color-muted)] hover:text-red-400' }}">
                                    <span>{{ $hasHeart ? '❤️' : '🤍' }}</span>
                                    <span>{{ $wish->heart_count }}</span>
                                </button>
                                <button wire:click="react({{ $wish->id }}, 'congrats')"
                                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 border transition-all
                                        {{ $hasCongrats ? 'border-yellow-300 bg-yellow-50 text-yellow-600' : 'border-[var(--color-border)] hover:border-yellow-200 text-[var(--color-muted)] hover:text-yellow-500' }}">
                                    <span>🎊</span>
                                    <span>{{ $wish->congrats_count }}</span>
                                </button>
                                <p class="text-xs" style="color: var(--color-muted);">{{ $wish->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($wishes->hasPages())
                <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:2rem;flex-wrap:wrap;">
                    @if($wishes->onFirstPage())
                        <span style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">← Prev</span>
                    @else
                        <button wire:click="previousPage" style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;">← Prev</button>
                    @endif
                    <span style="font-size:12px;color:var(--color-muted);">
                        Page {{ $wishes->currentPage() }} of {{ $wishes->lastPage() }}
                        &nbsp;·&nbsp; {{ $wishes->total() }} wishes
                    </span>
                    @if($wishes->hasMorePages())
                        <button wire:click="nextPage" style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;cursor:pointer;background:none;">Next →</button>
                    @else
                        <span style="padding:6px 16px;border:1px solid var(--color-border);font-size:12px;opacity:0.4;cursor:not-allowed;">Next →</span>
                    @endif
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-10" style="color: var(--color-muted);">
                <p class="text-sm">No wishes yet. Be the first! 💛</p>
            </div>
            @endif

            {{-- ── Gift Section ── --}}
            @if($accounts->count() || $paymentLinks->count())
            <div class="mt-16 pt-12 border-t border-[var(--color-border)]">
                <div class="text-center mb-8">
                    <p class="section-eyebrow mb-2">Bless the Couple</p>
                    <h3 class="section-title" style="font-size:1.5rem;">Care to Gift Us?</h3>
                    <p style="color:var(--color-muted);font-size:0.9375rem;margin-top:0.5rem;line-height:1.8;">
                        Your presence is the greatest gift of all. But if you'd like to bless us further:
                    </p>
                </div>

                {{-- Bank Accounts --}}
                @if($accounts->count())
                <div class="space-y-3 mb-6">
                    @foreach($accounts->groupBy('currency') as $currency => $currAccounts)
                    <p class="text-xs uppercase tracking-widest mb-3 flex items-center gap-3" style="color:var(--color-gold);">
                        <span>{{ $currency === 'NGN' ? '🇳🇬 Naira' : ($currency === 'USD' ? '🇺🇸 USD' : '🇬🇧 GBP') }}</span>
                        <span class="flex-1 h-px bg-[var(--color-border)]"></span>
                    </p>
                    @foreach($currAccounts as $account)
                    <div class="border border-[var(--color-border)] p-5 bg-white">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <p class="font-medium text-sm">{{ $account->bank_name }}</p>
                                <p style="color:var(--color-muted);font-size:0.875rem;">{{ $account->account_name }}</p>
                                @if($account->account_number)
                                <p style="font-family:var(--font-serif);font-size:1.25rem;letter-spacing:0.04em;margin-top:4px;">{{ $account->account_number }}</p>
                                @endif
                            </div>
                            @if($account->account_number)
                            <button onclick="copyToClipboard('{{ $account->account_number }}')"
                                    class="btn-outline-gold text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Copy
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @endforeach
                </div>
                @endif

                {{-- Payment Links --}}
                @if($paymentLinks->count())
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-widest mb-3 flex items-center gap-3" style="color:var(--color-gold);">
                        <span>🔗 Send Online</span>
                        <span class="flex-1 h-px bg-[var(--color-border)]"></span>
                    </p>
                    @foreach($paymentLinks as $link)
                    <div class="border border-[var(--color-border)] p-5 bg-white flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($link->currency_tag)
                                <span class="text-xs px-2 py-0.5" style="border:1px solid var(--color-gold);color:var(--color-gold);">{{ $link->currency_tag }}</span>
                                @endif
                                <p class="font-medium text-sm">{{ $link->title }}</p>
                            </div>
                            @if($link->description)
                            <p class="text-xs mt-0.5" style="color:var(--color-muted);">{{ $link->description }}</p>
                            @endif
                        </div>
                        <a href="{{ $link->url }}" target="_blank" rel="noopener"
                           class="btn-gold text-xs py-2 px-5 inline-flex items-center gap-2 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Send Gift
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif

                <p class="text-center text-xs mt-6" style="color:var(--color-muted);">
                    Full gift details also on our <a href="{{ route('our-story') }}" style="color:var(--color-gold);">Our Story</a> page.
                </p>
            </div>
            @endif
        </div>
    </section>
</div>