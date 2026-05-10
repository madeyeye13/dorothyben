<div>
    {{-- Hero Section --}}
    <section class="hero-section" style="background-image: url('{{ $heroUrl }}'); background-size: cover; background-position: center;">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="section-eyebrow mb-4" style="color: var(--color-gold-light);">Together Forever</p>
            <h1 style="font-family: var(--font-serif); font-size: clamp(3rem, 8vw, 6rem); font-weight: 400; color: #fff; line-height: 1.1; margin-bottom: 0.5rem;">
                Dorothy
                <span style="font-style: italic; color: var(--color-gold);">&</span>
                Ben
            </h1>
            <p style="font-family: var(--font-sans); color: rgba(255,255,255,0.7); font-size: 0.9rem; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 0.5rem;">
                {{ config('wedding.hashtag') }}
            </p>
            <div class="gold-divider my-6" style="max-width: 300px; margin-left: auto; margin-right: auto;">
                <span style="font-size: 0.75rem; letter-spacing: 0.2em; color: rgba(255,255,255,0.5); white-space: nowrap;">
                    {{ strtoupper(config('wedding.wedding_date')) }}
                </span>
            </div>
            <p style="color: rgba(255,255,255,0.6); font-size: 0.875rem; letter-spacing: 0.1em; margin-bottom: 2.5rem;">
                {{ config('wedding.general_location') }}
            </p>
            @if($rsvpEnabled)
            <a href="{{ route('rsvp') }}" class="btn-gold" style="font-size: 0.8125rem; letter-spacing: 0.15em; padding: 1rem 3rem;">
                RSVP Now
            </a>
            @else
            <p style="color: rgba(255,255,255,0.4); font-size: 0.875rem; letter-spacing: 0.1em;">RSVP is currently closed</p>
            @endif
        </div>
    </section>

    {{-- Leave A Wish Section --}}
    <section class="py-24 text-center" style="background: var(--color-ivory);">
        <div class="max-w-xl mx-auto px-6">
            <p class="section-eyebrow mb-3">Share Your Love</p>
            <h2 class="section-title mb-4">Leave a Wish</h2>
            <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 2rem; line-height: 1.8;">
                Your words mean the world to us. Share a message, blessing, or memory with Dorothy and Ben.
            </p>
            <a href="{{ route('wishes') }}" class="btn-outline-gold">
                Write a Wish
            </a>
        </div>
    </section>

    {{-- Travelling Guests Section --}}
    <section style="background: var(--color-obsidian); padding: 5rem 1.5rem;">
        <div class="max-w-2xl mx-auto text-center">
            <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Travelling From Afar?</p>
            <h2 class="section-title mb-4" style="color: #fff;">Find Nearby Hotels</h2>
            <p style="color: rgba(255,255,255,0.55); font-size: 0.9375rem; margin-bottom: 2.5rem; line-height: 1.8;">
                Coming from out of town? We've made it easy to find accommodation close to our reception venue in Wuse 2, Abuja.
            </p>
            <a href="https://www.google.com/maps/search/hotels+near+Space+and+Function+Wuse+2+Abuja/@{{ config('wedding.reception.lat') }},{{ config('wedding.reception.lng') }},15z"
               target="_blank" rel="noopener"
               class="btn-gold inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Find Hotels Near Venue
            </a>
            <p style="color: rgba(255,255,255,0.35); font-size: 0.75rem; margin-top: 1rem;">Opens Google Maps</p>
        </div>
    </section>

    {{-- Colour of the Day --}}
    <section style="padding: 5rem 1.5rem; background: #fff; border-top: 1px solid #E2D9C8; text-align: center;">
        <div style="max-width: 640px; margin: 0 auto;">
            <p style="font-family: var(--font-sans); font-size: 0.75rem; font-weight: 500; letter-spacing: 0.15em; text-transform: uppercase; color: var(--color-gold); margin-bottom: 0.75rem;">
                Dress Code
            </p>
            <h2 style="font-family: var(--font-serif); font-size: clamp(1.75rem, 4vw, 2.75rem); font-weight: 400; color: var(--color-obsidian); margin-bottom: 1rem;">
                Colour of the Day
            </h2>
            <p style="color: var(--color-muted); font-size: 0.9375rem; margin-bottom: 3rem; line-height: 1.8;">
                We'd love for you to celebrate with us dressed in our wedding colours.
            </p>

            {{-- Colour swatches --}}
            <div style="display: flex; align-items: flex-start; justify-content: center; gap: 4rem; flex-wrap: wrap;">

                {{-- Touch of Green --}}
                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.875rem;">
                    <div style="width: 88px; height: 88px; border-radius: 50%; background-color: #4A7C59; box-shadow: 0 0 0 3px #fff, 0 0 0 5px #4A7C59, 0 8px 20px rgba(74,124,89,0.25);"></div>
                    <div>
                        <p style="font-family: var(--font-sans); font-size: 0.9375rem; font-weight: 500; color: var(--color-obsidian); margin: 0 0 0.25rem;">Touch of Green</p>
                        <p style="font-family: var(--font-sans); font-size: 0.8125rem; color: var(--color-muted); margin: 0; letter-spacing: 0.05em;">#4A7C59</p>
                    </div>
                </div>

                {{-- Gold --}}
                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.875rem;">
                    <div style="width: 88px; height: 88px; border-radius: 50%; background-color: #C9A84C; box-shadow: 0 0 0 3px #fff, 0 0 0 5px #C9A84C, 0 8px 20px rgba(201,168,76,0.25);"></div>
                    <div>
                        <p style="font-family: var(--font-sans); font-size: 0.9375rem; font-weight: 500; color: var(--color-obsidian); margin: 0 0 0.25rem;">Gold</p>
                        <p style="font-family: var(--font-sans); font-size: 0.8125rem; color: var(--color-muted); margin: 0; letter-spacing: 0.05em;">#C9A84C</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Countdown Section --}}
    <section class="py-20 text-center" style="background: var(--color-ivory);">
        <div class="max-w-3xl mx-auto px-6">
            <p class="section-eyebrow mb-3">The Big Day</p>
            <h2 class="section-title mb-10">Counting Down</h2>
            <div x-data="countdown('2026-07-10')" class="grid grid-cols-4 gap-4 max-w-lg mx-auto">
                <template x-for="(unit, key) in units" :key="key">
                    <div class="flex flex-col items-center">
                        <div class="border border-[var(--color-border)] bg-white w-full py-5 flex items-center justify-center">
                            <span x-text="unit.value" style="font-family: var(--font-serif); font-size: 2rem; font-weight: 400; color: var(--color-gold);"></span>
                        </div>
                        <p x-text="unit.label" class="text-xs mt-2 uppercase tracking-widest" style="color: var(--color-muted);"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <script>
    function countdown(targetDate) {
        return {
            units: { days: { value: 0, label: 'Days' }, hours: { value: 0, label: 'Hours' }, minutes: { value: 0, label: 'Mins' }, seconds: { value: 0, label: 'Secs' } },
            init() {
                this.update();
                setInterval(() => this.update(), 1000);
            },
            update() {
                const diff = new Date(targetDate) - new Date();
                if (diff <= 0) { Object.keys(this.units).forEach(k => this.units[k].value = '00'); return; }
                const d = Math.floor(diff / 86400000);
                const h = Math.floor((diff % 86400000) / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                this.units.days.value    = String(d).padStart(2, '0');
                this.units.hours.value   = String(h).padStart(2, '0');
                this.units.minutes.value = String(m).padStart(2, '0');
                this.units.seconds.value = String(s).padStart(2, '0');
            }
        }
    }
    </script>
</div>