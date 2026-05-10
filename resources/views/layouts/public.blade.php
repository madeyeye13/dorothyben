<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dorothy & Ben — {{ config('wedding.wedding_date') }}</title>
    <meta name="description" content="Join us as Dorothy & Ben celebrate their wedding on {{ config('wedding.wedding_date') }} in {{ config('wedding.general_location') }}">
    <link rel="icon" href="{{ asset('images/D&B.png') }}" type="image/png">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[var(--color-ivory)] text-[var(--color-obsidian)]">

    {{-- Navigation --}}
    <nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-[var(--color-obsidian)]/95 backdrop-blur-sm border-b border-white/10">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/D&B.png') }}" alt="D&B" class="h-9 w-auto" onerror="this.style.display='none'">
                <span style="font-family: var(--font-serif); color: var(--color-gold); font-size: 1.25rem; letter-spacing: 0.05em;">D & B</span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-8">
                @foreach([
                    ['route' => 'home',      'label' => 'Home'],
                    ['route' => 'rsvp',      'label' => 'RSVP'],
                    ['route' => 'our-story', 'label' => 'Our Story'],
                    ['route' => 'wishes',    'label' => 'Wishes'],
                    ['route' => 'gallery',   'label' => 'Gallery'],
                    ['route' => 'memories',  'label' => 'Memories'],
                ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="text-white/80 hover:text-[var(--color-gold)] transition-colors text-sm tracking-wide font-light
                          {{ request()->routeIs($item['route']) ? '!text-[var(--color-gold)]' : '' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </div>

            {{-- RSVP CTA --}}
            <a href="{{ route('rsvp') }}" class="hidden md:flex btn-gold text-xs py-2.5 px-5">
                RSVP Now
            </a>

            {{-- Mobile Toggle --}}
            <button @click="open = !open" class="md:hidden text-white p-1">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-cloak x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-cloak x-transition class="md:hidden bg-[var(--color-charcoal)] border-t border-white/10">
            <div class="flex flex-col px-6 py-4 gap-4">
                @foreach([
                    ['route' => 'home',      'label' => 'Home'],
                    ['route' => 'rsvp',      'label' => 'RSVP'],
                    ['route' => 'our-story', 'label' => 'Our Story'],
                    ['route' => 'wishes',    'label' => 'Wishes'],
                    ['route' => 'gallery',   'label' => 'Gallery'],
                    ['route' => 'memories',  'label' => 'Memories'],
                ] as $item)
                <a href="{{ route($item['route']) }}" @click="open = false"
                   class="text-white/80 hover:text-[var(--color-gold)] text-sm py-1 tracking-wide">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-[73px]">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-[var(--color-charcoal)] text-white/60 py-10 mt-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p style="font-family: var(--font-serif); color: var(--color-gold); font-size: 1.5rem;" class="mb-2">Dorothy & Ben</p>
            <p class="text-sm mb-1" style="letter-spacing: 0.1em;">{{ config('wedding.hashtag') }}</p>
            <p class="text-sm mb-6">{{ config('wedding.wedding_date') }} · {{ config('wedding.general_location') }}</p>
            <div class="border-t border-white/10 pt-6 flex flex-col items-center gap-1">
                <p class="text-xs text-white/40">Website created with love by <span class="text-[var(--color-gold-light)]">Bezalel Koncept</span></p>
                <p class="text-xs text-white/40">
                    Event Planner:
                    <a href="{{ config('wedding.pk_events_instagram') }}" target="_blank" rel="noopener"
                       class="text-[var(--color-gold)] hover:underline">PK Events</a>
                </p>
            </div>
        </div>
    </footer>

    {{-- Toast Notifications --}}
    <div
        x-data="toastManager()"
        @toast.window="addToast($event.detail)"
        style="position:fixed;top:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.75rem;pointer-events:none;max-width:360px;"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0"
                style="pointer-events:auto;display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;box-shadow:0 4px 20px rgba(0,0,0,0.25);"
                :style="toast.type === 'success'
                    ? 'background:#0D0D0D; border-left: 3px solid #C9A84C;'
                    : toast.type === 'error'
                    ? 'background:#1a0505; border-left: 3px solid #ef4444;'
                    : 'background:#1a1200; border-left: 3px solid #f59e0b;'"
            >
                {{-- Icon circle --}}
                <div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;"
                     :style="toast.type === 'success'
                         ? 'background:rgba(201,168,76,0.15);'
                         : toast.type === 'error'
                         ? 'background:rgba(239,68,68,0.15);'
                         : 'background:rgba(245,158,11,0.15);'">
                    {{-- Success checkmark --}}
                    <svg x-show="toast.type === 'success'" style="width:14px;height:14px;color:#C9A84C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{-- Error X --}}
                    <svg x-show="toast.type === 'error'" style="width:14px;height:14px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{-- Warning ! --}}
                    <svg x-show="toast.type === 'warning'" style="width:14px;height:14px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                {{-- Message --}}
                <p style="color:#fff;font-size:0.875rem;font-weight:300;margin:0;line-height:1.5;" x-text="toast.message"></p>
            </div>
        </template>
    </div>

    <script>
    function toastManager() {
        return {
            toasts: [],
            addToast({ message, type = 'success' }) {
                const id = Date.now();
                this.toasts.push({ id, message, type, show: true });
                setTimeout(() => {
                    const t = this.toasts.find(t => t.id === id);
                    if (t) t.show = false;
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                }, 4000);
            }
        }
    }
    </script>

    @livewireScripts
</body>
</html>