<div>
    {{-- Hero --}}
    <section class="relative" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Our Journey</p>
            <h1 style="font-family: var(--font-serif); font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 400; color: #fff; line-height: 1.15;">
                Dorothy <em style="color: var(--color-gold);">&</em> Ben
            </h1>
            <p style="color: rgba(255,255,255,0.6); letter-spacing: 0.15em; font-size: 0.8125rem; text-transform: uppercase; margin-top: 1rem;">
                {{ config('wedding.hashtag') }}
            </p>
        </div>
    </section>

    {{-- Story Chapters --}}
    <section class="py-24" style="background: var(--color-ivory);">
        <div class="max-w-3xl mx-auto px-6">

            {{-- Chapter 1 --}}
            <div class="mb-20 text-center">
                <p class="section-eyebrow mb-2">Chapter One</p>
                <h2 class="section-title mb-2"><em>How It All Began</em></h2>
                <div class="gold-divider my-8"><span style="color: var(--color-gold); font-size: 1rem;">✦</span></div>
                <div class="space-y-5" style="color: #3a3a3a; font-size: 1rem; line-height: 1.9;">
                    <p>On one fateful Sunday in 2018, the universe quietly arranged a meeting that would change everything. Dorothy had heard through a mutual friend that a new student, Ben, would be joining her university for his master's programme, and that his first degree was also in pharmacy, just like hers. Curiosity was already quietly stirring.</p>
                    <p>That Sunday turned out to be Ben's very first at the African Catholic Mass, typically held on the last Sunday of every month. He had already been welcomed into the choir group, and as voices rose in worship, he found himself glancing across the choir to a face he had never seen before. Dorothy did not attend the last rehearsal, so Ben was puzzled: who was she?</p>
                    <p>Meanwhile, Dorothy had spotted the unfamiliar face and, with characteristic warmth and confidence, walked over after Mass, introduced herself, and opened a conversation about his pharmacy degree experience.</p>
                    <p>It was a simple, brave gesture, and Ben would later confess that it was the very opening he had been quietly hoping for.</p>
                </div>
            </div>

            {{-- Chapter 2 --}}
            <div class="mb-20 text-center">
                <p class="section-eyebrow mb-2">Chapter Two</p>
                <h2 class="section-title mb-2"><em>The Journey to Love</em></h2>
                <div class="gold-divider my-8"><span style="color: var(--color-gold); font-size: 1rem;">✦</span></div>
                <div class="space-y-5" style="color: #3a3a3a; font-size: 1rem; line-height: 1.9;">
                    <p>Ben, it turned out, was a born storyteller. From the lively corridors of pharmacy school to the adventures of his NYSC service year, Dorothy found herself living vicariously through every tale. He had a way of making even the ordinary feel like an adventure worth hearing.</p>
                    <p>And so a beautiful friendship took root, one built on honest conversations and shared laughter. From walks between Cotton Mills and Alberta Terrace, their worlds slowly folded into each other. What had blossomed as friendship deepened, quite naturally, into something far more.</p>
                    <p>After seeking God's direction together, they chose to step forward, and in February 2019, Dorothy and Ben officially began their love story.</p>
                </div>
            </div>

            {{-- Chapter 3 --}}
            <div class="text-center">
                <p class="section-eyebrow mb-2">Chapter Three</p>
                <h2 class="section-title mb-2"><em>The Proposal</em></h2>
                <div class="gold-divider my-8"><span style="color: var(--color-gold); font-size: 1rem;">✦</span></div>
                <div class="space-y-5" style="color: #3a3a3a; font-size: 1rem; line-height: 1.9;">
                    <p>After five years, through the warmth of being close and the ache of distance, Dorothy and Ben held on to each other and to what they knew was real.</p>
                    <p>Then came the moment Ben had been preparing for: a proposal that was also a promise — not only to spend forever together but also to finally close the miles between them, once and for all. Dorothy said yes, wholeheartedly.</p>
                    <p>Now in 2026, with hearts full of gratitude, Dorothy and Ben joyfully invite you to celebrate this next and most beautiful chapter with them. Your presence, your love and your witness on that day would truly be the greatest gift of all.</p>
                </div>
            </div>

        </div>
    </section>

    {{-- Wedding Event Details --}}
    <section style="background: var(--color-obsidian); padding: 5rem 1.5rem;">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">The Celebration</p>
                <h2 class="section-title" style="color: #fff;">Wedding Details</h2>
                <p style="color: rgba(255,255,255,0.45); font-size: 0.875rem; margin-top: 0.75rem;">Venue details visible to confirmed guests only</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Church --}}
                <div class="border border-white/10 p-8">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 border border-[var(--color-gold)]/40 flex items-center justify-center">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-widest" style="color: var(--color-gold);">Church Wedding</p>
                        </div>
                    </div>
                    <p style="font-family: var(--font-serif); font-size: 1.125rem; color: #fff; margin-bottom: 0.75rem;">
                        {{ config('wedding.church.name') }}
                    </p>
                    <div class="space-y-2 text-sm" style="color: rgba(255,255,255,0.55);">
                        <p>🕙 {{ config('wedding.church.time') }}</p>
                        <p>📍 {{ config('wedding.general_location') }}</p>
                        <p class="text-xs mt-3 border-t border-white/10 pt-3" style="color: rgba(255,255,255,0.3);">
                            Full address provided to confirmed guests via RSVP
                        </p>
                    </div>
                </div>

                {{-- Reception --}}
                <div class="border border-[var(--color-gold)]/30 p-8" style="background: rgba(201,168,76,0.04);">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 border border-[var(--color-gold)]/40 flex items-center justify-center">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-1.5-.454M9 6l3-3m0 0l3 3m-3-3v14"/></svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-widest" style="color: var(--color-gold);">Reception</p>
                        </div>
                    </div>
                    <p style="font-family: var(--font-serif); font-size: 1.125rem; color: #fff; margin-bottom: 0.75rem;">
                        {{ config('wedding.reception.name') }}
                    </p>
                    <div class="space-y-2 text-sm" style="color: rgba(255,255,255,0.55);">
                        <p>🕑 {{ config('wedding.reception.time') }}</p>
                        <p>🚪 Doors open: {{ config('wedding.reception.doors') }}</p>
                        <p>📍 {{ config('wedding.general_location') }}</p>
                        <p class="text-xs mt-3 border-t border-white/10 pt-3" style="color: rgba(255,255,255,0.3);">
                            Full address provided to confirmed guests via RSVP
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('rsvp') }}" class="btn-gold">Confirm Your Attendance</a>
            </div>
        </div>
    </section>

    {{-- Gift Section --}}
    <section class="py-24" style="background: var(--color-ivory);">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="section-eyebrow mb-3">Care to Gift Us?</p>
                <h2 class="section-title mb-4">Gift Information</h2>
                <p style="color: var(--color-muted); max-width: 480px; margin: 0 auto; line-height: 1.8; font-size: 0.9375rem;">
                    Your presence is the greatest gift of all. However, if you would like to bless us further, below are our account details.
                </p>
            </div>

            @if($accounts->count())
            <div class="space-y-4">
                @foreach($accounts->groupBy('currency') as $currency => $currAccounts)
                <div>
                    <p class="text-xs uppercase tracking-widest mb-4 flex items-center gap-3" style="color: var(--color-gold);">
                        <span>{{ $currency === 'NGN' ? '🇳🇬 Nigerian Naira' : ($currency === 'USD' ? '🇺🇸 US Dollar' : '🇬🇧 British Pound') }}</span>
                        <span class="flex-1 h-px bg-[var(--color-border)]"></span>
                    </p>
                    @foreach($currAccounts as $account)
                    <div class="border border-[var(--color-border)] p-6 mb-3 bg-white">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1 flex-1">
                                <p class="font-medium text-sm">{{ $account->bank_name }}</p>
                                <p style="color: var(--color-muted); font-size: 0.875rem;">{{ $account->account_name }}</p>
                                @if($account->account_number)
                                <p style="font-family: var(--font-serif); font-size: 1.5rem; letter-spacing: 0.05em; color: var(--color-obsidian); margin-top: 0.5rem;">
                                    {{ $account->account_number }}
                                </p>
                                @endif
                                @if($account->sort_code)
                                <p style="color: var(--color-muted); font-size: 0.8125rem;">Sort Code: {{ $account->sort_code }}</p>
                                @endif
                                @if($account->routing_number)
                                <p style="color: var(--color-muted); font-size: 0.8125rem;">Routing: {{ $account->routing_number }}</p>
                                @endif
                            </div>
                            @if($account->account_number)
                            <button onclick="copyToClipboard('{{ $account->account_number }}')"
                                    class="shrink-0 border border-[var(--color-border)] hover:border-[var(--color-gold)] p-2.5 transition-colors group"
                                    title="Copy account number">
                                <svg class="w-4 h-4 group-hover:text-[var(--color-gold)] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @endif

            {{-- Payment Links — separate from bank accounts --}}
            @if(isset($paymentLinks) && $paymentLinks->count())
            <div class="mt-8">
                <p class="text-xs uppercase tracking-widest mb-4 flex items-center gap-3" style="color: var(--color-gold);">
                    <span>🔗 Send Money Online</span>
                    <span class="flex-1 h-px bg-[var(--color-border)]"></span>
                </p>
                <div class="space-y-3">
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
            </div>
            @endif

            {{-- Empty state --}}
            @if(!$accounts->count() && (!isset($paymentLinks) || !$paymentLinks->count()))
            <div class="text-center py-10 border border-[var(--color-border)]" style="color: var(--color-muted);">
                <p class="text-sm">Account details coming soon.</p>
            </div>
            @endif
        </div>
    </section>
</div>