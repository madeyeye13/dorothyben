<!DOCTYPE html>
<html lang="en" x-data="adminApp()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Dorothy & Ben Wedding</title>
    <link rel="icon" href="{{ asset('images/D&B.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }

        body { margin: 0; padding: 0; font-family: 'DM Sans', sans-serif; }

        /* Dark mode */
        .dark body, .dark .admin-page { background: #111 !important; color: #e0e0e0; }
        .dark .admin-topbar  { background: #1a1a1a !important; border-color: #2a2a2a !important; }
        .dark .admin-content { background: #111 !important; }
        .dark .admin-card, .dark .bg-white { background: #1a1a1a !important; border-color: #2a2a2a !important; }
        .dark .form-input    { background: #222 !important; border-color: #333 !important; color: #e0e0e0 !important; }
        .dark table thead tr { background: #1a1a1a !important; }
        .dark table tbody tr { border-color: #2a2a2a !important; }
        .dark table tbody tr:hover { background: #1e1e1e !important; }

        /* Sidebar nav */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            font-size: 14px;
            color: rgba(255,255,255,0.6);
            border-left: 2px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.07); }
        .nav-item.active { color: #C9A84C; border-left-color: #C9A84C; background: rgba(201,168,76,0.09); }

        /* Layout shells */
        .layout-shell {
            display: flex;
            min-height: 100vh;
        }

        /* Desktop sidebar — always visible ≥1024px */
        .desktop-sidebar {
            flex-shrink: 0;
            background: #1C1C1C;
            display: none; /* hidden by default (mobile) */
            flex-direction: column;
            transition: width 0.25s ease;
            overflow: hidden;
        }
        @media (min-width: 1024px) {
            .desktop-sidebar { display: flex; }
        }

        /* Main area */
        .main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        /* Topbar */
        .admin-topbar {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid #E2D9C8;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 30;
            flex-shrink: 0;
        }

        /* Page content */
        .admin-content {
            flex: 1;
            padding: 24px;
            background: #f5f3ef;
            overflow-y: auto;
        }

        /* Mobile drawer */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 40;
        }
        .mobile-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            background: #1C1C1C;
            z-index: 50;
            display: flex;
            flex-direction: column;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            overflow-y: auto;
        }
        .mobile-drawer.open  { transform: translateX(0); }
        .mobile-overlay.open { display: block; }

        /* Hamburger: show only <1024px */
        .hamburger-btn { display: flex; }
        @media (min-width: 1024px) { .hamburger-btn { display: none; } }

        /* Desktop sidebar toggle: show only ≥1024px */
        .desktop-toggle { display: none; }
        @media (min-width: 1024px) { .desktop-toggle { display: flex; } }

        /* Mobile logo: show only <1024px */
        .mobile-logo { display: flex; }
        @media (min-width: 1024px) { .mobile-logo { display: none; } }

        /* Desktop title: show only ≥1024px */
        .desktop-title { display: none; }
        @media (min-width: 1024px) { .desktop-title { display: block; } }

        /* User info: hide on very small */
        .user-info { display: none; }
        @media (min-width: 640px) { .user-info { display: block; } }
    </style>
</head>
<body>

@php
$isScanner = auth()->user()?->hasRole('scanner') && !auth()->user()?->hasRole('admin') && !auth()->user()?->hasRole('super-admin');

$navItems = $isScanner ? [
    ['route' => 'admin.scan',         'label' => 'QR Scanner',   'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
    ['route' => 'admin.guest-lookup', 'label' => 'Guest Lookup', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
] : [
    ['route' => 'admin.dashboard', 'label' => 'Dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'admin.guests',    'label' => 'Guests',        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ['route' => 'admin.wishes',    'label' => 'Wishes',        'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
    ['route' => 'admin.gallery',   'label' => 'Gallery',       'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['route' => 'admin.memories',  'label' => 'Memories',      'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
    ['route' => 'admin.accounts',      'label' => 'Gift Accounts',   'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
    ['route' => 'admin.payment-links', 'label' => 'Payment Links',   'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
    ['route' => 'admin.settings',  'label' => 'Site Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ['route' => 'admin.users',     'label' => 'Admin Users',   'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    ['route' => 'admin.profile',      'label' => 'My Profile',    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ['route' => 'admin.scan',         'label' => 'QR Scanner',    'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
    ['route' => 'admin.guest-lookup', 'label' => 'Guest Lookup',  'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
]; // end full admin nav
@endphp

{{-- Mobile overlay --}}
<div id="mobile-overlay" class="mobile-overlay" onclick="closeMobileMenu()"></div>

{{-- Mobile drawer --}}
<div id="mobile-drawer" class="mobile-drawer">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.08);">
        <div style="display:flex;align-items:center;gap:8px;">
            <img src="{{ asset('images/D&B.png') }}" alt="D&B" style="height:28px;" onerror="this.style.display='none'">
            <span style="font-family:'Playfair Display',serif;color:#C9A84C;font-size:1rem;">D & B</span>
        </div>
        <button onclick="closeMobileMenu()" style="background:none;border:none;cursor:pointer;padding:4px;color:rgba(255,255,255,0.5);">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <nav style="flex:1;padding:8px 0;">
        @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}" onclick="closeMobileMenu()"
           class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
            </svg>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>
    <div style="border-top:1px solid rgba(255,255,255,0.08);padding:8px 0;">
        <a href="{{ route('admin.logout') }}" class="nav-item" style="color:rgba(239,68,68,0.75);">
            <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </div>
</div>

{{-- Main layout shell --}}
<div class="layout-shell" :style="darkMode ? 'background:#111;' : 'background:#f5f3ef;'">

    {{-- Desktop sidebar --}}
    <aside class="desktop-sidebar" :style="sidebarOpen ? 'width:260px' : 'width:64px'">
        {{-- Logo --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 14px;border-bottom:1px solid rgba(255,255,255,0.08);min-height:60px;">
            <a href="{{ route('admin.dashboard') }}" x-show="sidebarOpen"
               style="display:flex;align-items:center;gap:8px;text-decoration:none;overflow:hidden;">
                <img src="{{ asset('images/D&B.png') }}" alt="D&B" style="height:28px;flex-shrink:0;" onerror="this.style.display='none'">
                <span style="font-family:'Playfair Display',serif;color:#C9A84C;font-size:1rem;white-space:nowrap;">D & B</span>
            </a>
            <button @click="sidebarOpen=!sidebarOpen;localStorage.setItem('sidebarOpen',sidebarOpen)"
                    style="background:none;border:none;cursor:pointer;padding:4px;color:rgba(255,255,255,0.4);flex-shrink:0;margin-left:auto;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </button>
        </div>
        {{-- Nav --}}
        <nav style="flex:1;padding:8px 0;overflow-y:auto;overflow-x:hidden;">
            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                </svg>
                <span x-cloak x-show="sidebarOpen" style="overflow:hidden;white-space:nowrap;">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>
        {{-- Logout --}}
        <div style="border-top:1px solid rgba(255,255,255,0.08);padding:8px 0;">
            <a href="{{ route('admin.logout') }}" class="nav-item" style="color:rgba(239,68,68,0.75);">
                <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-cloak x-show="sidebarOpen">Logout</span>
            </a>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="main-area">

        {{-- Topbar --}}
        <header class="admin-topbar" :style="darkMode ? 'background:#1a1a1a;border-color:#2a2a2a;' : 'background:#fff;border-color:#E2D9C8;'">
            <div style="display:flex;align-items:center;gap:12px;">

                {{-- Hamburger (mobile only) --}}
                <button class="hamburger-btn" onclick="openMobileMenu()"
                        style="background:none;border:none;cursor:pointer;padding:6px;align-items:center;"
                        :style="darkMode ? 'color:#e0e0e0' : 'color:#0D0D0D'">
                    <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Mobile logo --}}
                <a class="mobile-logo" href="{{ route('admin.dashboard') }}"
                   style="align-items:center;gap:8px;text-decoration:none;">
                    <img src="{{ asset('images/D&B.png') }}" alt="D&B" style="height:28px;" onerror="this.style.display='none'">
                    <span style="font-family:'Playfair Display',serif;color:#C9A84C;font-size:1rem;">D & B</span>
                </a>

                {{-- Desktop title --}}
                <div class="desktop-title">
                    <p style="font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#C9A84C;margin:0 0 1px;">Admin Portal</p>
                    <p style="font-family:'Playfair Display',serif;font-size:17px;margin:0;" :style="darkMode ? 'color:#e0e0e0' : 'color:#0D0D0D'">Dorothy & Ben</p>
                </div>
            </div>

            {{-- Right side --}}
            <div style="display:flex;align-items:center;gap:10px;">
                {{-- Dark mode --}}
                <button @click="darkMode=!darkMode;localStorage.setItem('darkMode',darkMode)"
                        style="background:none;border:none;cursor:pointer;padding:6px;display:flex;align-items:center;"
                        :style="darkMode ? 'color:#fbbf24' : 'color:#6B6B6B'">
                    <svg x-show="!darkMode" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {{-- View site --}}
                <a href="{{ route('home') }}" target="_blank"
                   style="font-size:12px;padding:5px 10px;border:1px solid;text-decoration:none;white-space:nowrap;"
                   :style="darkMode ? 'border-color:#333;color:#888;' : 'border-color:#E2D9C8;color:#6B6B6B;'">
                    View Site ↗
                </a>

                {{-- User --}}
                <div class="user-info" style="text-align:right;">
                    <p style="font-size:12px;font-weight:500;margin:0;" :style="darkMode ? 'color:#e0e0e0' : 'color:#0D0D0D'">{{ auth()->user()?->name }}</p>
                    <p style="font-size:11px;margin:0;color:#6B6B6B;">{{ auth()->user()?->email }}</p>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <div class="admin-content" :style="darkMode ? 'background:#111;' : 'background:#f5f3ef;'">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- Toast --}}
<div x-data="toastManager()" @toast.window="addToast($event.detail)"
     style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;max-width:360px;pointer-events:none;">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             style="pointer-events:auto;display:flex;align-items:center;gap:12px;padding:14px 16px;box-shadow:0 4px 20px rgba(0,0,0,0.25);"
             :style="toast.type==='success'
                ? 'background:#0D0D0D;border-left:3px solid #C9A84C;'
                : toast.type==='error'
                ? 'background:#1a0505;border-left:3px solid #ef4444;'
                : 'background:#1a1200;border-left:3px solid #f59e0b;'">
            <div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;"
                 :style="toast.type==='success' ? 'background:rgba(201,168,76,0.15);' : toast.type==='error' ? 'background:rgba(239,68,68,0.15);' : 'background:rgba(245,158,11,0.15);'">
                <svg x-show="toast.type==='success'" style="width:14px;height:14px;color:#C9A84C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <svg x-show="toast.type==='error'" style="width:14px;height:14px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <svg x-show="toast.type==='warning'" style="width:14px;height:14px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <p style="color:#fff;font-size:14px;font-weight:300;margin:0;line-height:1.5;" x-text="toast.message"></p>
        </div>
    </template>
</div>

<script>
function adminApp() {
    return {
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        darkMode:    localStorage.getItem('darkMode') === 'true',
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
function openMobileMenu() {
    document.getElementById('mobile-drawer').classList.add('open');
    document.getElementById('mobile-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeMobileMenu() {
    document.getElementById('mobile-drawer').classList.remove('open');
    document.getElementById('mobile-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>

@livewireScripts
</body>
</html>