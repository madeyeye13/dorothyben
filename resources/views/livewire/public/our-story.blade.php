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


    {{-- Gift nudge --}}
    <section class="py-16" style="background: var(--color-ivory);">
        <div class="max-w-xl mx-auto px-6 text-center">
            <p class="section-eyebrow mb-3">Care to Gift Us?</p>
            <h2 class="section-title mb-4">Gift Information</h2>
            <p style="color: var(--color-muted); line-height: 1.8; font-size: 0.9375rem; margin-bottom: 2rem;">
                Your presence is the greatest gift of all. However, if you'd like to bless us further,
                we have a dedicated page with all our gift details.
            </p>
            <a href="{{ route('gifts') }}" class="btn-gold text-xs py-3 px-8 inline-flex items-center gap-2">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
                View Gift Details
            </a>
        </div>
    </section>
</div>