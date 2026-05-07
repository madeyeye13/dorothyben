<!DOCTYPE html>
<html lang="en" x-data="adminApp()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Dorothy & Ben Wedding</title>
    <link rel="icon" href="{{ asset('images/D&B.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .dark { color-scheme: dark; }
        .dark body, .dark .admin-main { background-color: #111; color: #e0e0e0; }
        .dark .admin-card { background: #1a1a1a; border-color: #2a2a2a; }
        .dark .form-input { background: #222; border-color: #333; color: #e0e0e0; }
        .dark .admin-table thead { background: #1a1a1a; }
        .dark .admin-table tbody tr { border-color: #2a2a2a; }
        .dark .admin-table tbody tr:hover { background: #1e1e1e; }
        .sidebar-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 1rem; font-size: 0.875rem; color: rgba(255,255,255,0.65); transition: all 0.15s; border-left: 2px solid transparent; }
        .sidebar-nav-item:hover { color: #fff; background: rgba(255,255,255,0.06); }
        .sidebar-nav-item.active { color: var(--color-gold); border-left-color: var(--color-gold); background: rgba(201,168,76,0.08); }
    </style>
</head>
<body>
<div class="flex min-h-screen" :class="darkMode ? 'bg-[#111] text-[#e0e0e0]' : 'bg-[#f5f3ef] text-[var(--color-obsidian)]'">

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="admin-sidebar shrink-0 flex flex-col transition-all duration-300 bg-[var(--color-charcoal)] relative z-30"
        style="min-height: 100vh;"
    >
        {{-- Logo Area --}}
        <div class="flex items-center justify-between px-4 py-5 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" x-show="sidebarOpen" class="flex items-center gap-2">
                <span style="font-family: var(--font-serif); color: var(--color-gold); font-size: 1.125rem;">D & B</span>
                <span class="text-white/40 text-xs">Admin</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)"
                    class="text-white/50 hover:text-white ml-auto p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">
            @php
            $navItems = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'admin.guests',    'label' => 'Guests',       'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.wishes',    'label' => 'Wishes',       'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['route' => 'admin.gallery',   'label' => 'Gallery',      'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'admin.memories',  'label' => 'Memories',     'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                ['route' => 'admin.accounts',  'label' => 'Gift Accounts','icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ['route' => 'admin.settings',  'label' => 'Site Settings','icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.users',     'label' => 'Admin Users',  'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['route' => 'admin.profile',   'label' => 'My Profile',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="sidebar-nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}"
               x-tooltip.right="sidebarOpen ? null : '{{ $item['label'] }}'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                </svg>
                <span x-show="sidebarOpen" x-transition.opacity class="truncate">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- Footer --}}
        <div class="border-t border-white/10 p-4">
            <a href="{{ route('admin.logout') }}"
               class="sidebar-nav-item text-red-400/70 hover:text-red-400 w-full">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="sidebarOpen" x-transition.opacity>Logout</span>
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between px-6 py-4 border-b"
                :class="darkMode ? 'bg-[#1a1a1a] border-[#2a2a2a]' : 'bg-white border-[var(--color-border)]'">
            <div>
                <p class="text-xs uppercase tracking-widest" style="color: var(--color-gold); font-family: var(--font-sans);">Admin Portal</p>
                <h1 class="text-lg font-medium" style="font-family: var(--font-serif);">Dorothy & Ben Wedding</h1>
            </div>
            <div class="flex items-center gap-4">
                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="p-2 rounded text-sm transition-colors"
                        :class="darkMode ? 'text-amber-300 hover:text-amber-200' : 'text-gray-500 hover:text-gray-700'">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {{-- View Site --}}
                <a href="{{ route('home') }}" target="_blank"
                   class="text-xs uppercase tracking-wider px-3 py-2 border transition-colors"
                   :class="darkMode ? 'border-[#333] text-gray-400 hover:text-white' : 'border-[var(--color-border)] text-[var(--color-muted)] hover:text-[var(--color-obsidian)]'">
                    View Site ↗
                </a>

                {{-- User --}}
                <div class="text-right">
                    <p class="text-xs font-medium">{{ auth()->user()?->name }}</p>
                    <p class="text-xs text-[var(--color-muted)]">{{ auth()->user()?->email }}</p>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="flex-1 p-6 admin-main overflow-auto"
             :class="darkMode ? 'bg-[#111]' : 'bg-[#f5f3ef]'">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- Toast --}}
<div
    x-data="toastManager()"
    @toast.window="addToast($event.detail)"
    class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
    style="max-width: 360px;"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-start gap-3 px-4 py-3 shadow-lg"
            :class="{
                'bg-[var(--color-obsidian)] border border-[var(--color-gold)]/40': toast.type === 'success',
                'bg-red-950 border border-red-500/40': toast.type === 'error',
                'bg-amber-950 border border-amber-500/40': toast.type === 'warning',
            }"
        >
            <span x-show="toast.type === 'success'" class="text-[var(--color-gold)] mt-0.5 shrink-0">✓</span>
            <span x-show="toast.type === 'error'" class="text-red-400 mt-0.5 shrink-0">✕</span>
            <p class="text-white text-sm font-light" x-text="toast.message"></p>
        </div>
    </template>
</div>

<script>
function adminApp() {
    return {
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        darkMode: localStorage.getItem('darkMode') === 'true',
    }
}
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
