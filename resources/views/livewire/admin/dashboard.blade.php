<div>
    <div class="mb-8">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">Dashboard</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">Overview of your wedding RSVPs and activity.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        @foreach([
            ['label' => 'Total Guests',     'value' => $stats['total_guests'],     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'var(--color-gold)'],
            ['label' => 'Attending',         'value' => $stats['attending'],         'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#10b981'],
            ['label' => 'Not Attending',     'value' => $stats['not_attending'],     'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#ef4444'],
            ['label' => 'Plus Guests',       'value' => $stats['total_companions'],  'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => '#8b5cf6'],
            ['label' => 'Wishes',            'value' => $stats['total_wishes'],      'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'color' => '#f59e0b'],
            ['label' => 'Gallery Photos',    'value' => $stats['gallery_count'],     'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => '#06b6d4'],
            ['label' => 'Memories',          'value' => $stats['memories_count'],    'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'color' => '#ec4899'],
            ['label' => 'Total Headcount',   'value' => $stats['attending'] + $stats['total_companions'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'var(--color-gold)'],
        ] as $stat)
        <div class="admin-card border border-[var(--color-border)] bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest mb-2" style="color: var(--color-muted);">{{ $stat['label'] }}</p>
                    <p style="font-family: var(--font-serif); font-size: 2rem; color: {{ $stat['color'] }}; line-height: 1;">{{ $stat['value'] }}</p>
                </div>
                <div class="w-9 h-9 flex items-center justify-center" style="background: {{ $stat['color'] }}15;">
                    <svg class="w-5 h-5" style="color: {{ $stat['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Attendance Percentage --}}
    @if($stats['total_guests'] > 0)
    <div class="admin-card border border-[var(--color-border)] bg-white p-6 mb-10">
        <p class="text-sm font-medium mb-3">Attendance Rate</p>
        <div class="w-full bg-gray-100 h-2 mb-2">
            <div class="h-2 transition-all" style="background: var(--color-gold); width: {{ round(($stats['attending'] / $stats['total_guests']) * 100) }}%;"></div>
        </div>
        <p class="text-xs" style="color: var(--color-muted);">
            {{ round(($stats['attending'] / $stats['total_guests']) * 100) }}% confirmed attendance ({{ $stats['attending'] }} of {{ $stats['total_guests'] }} respondents)
        </p>
    </div>
    @endif

    {{-- Recent Guests --}}
    <div class="admin-card border border-[var(--color-border)] bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[var(--color-border)]">
            <h3 class="text-sm font-medium">Recent RSVPs</h3>
            <a href="{{ route('admin.guests') }}" class="text-xs" style="color: var(--color-gold);">View All →</a>
        </div>
        <div class="divide-y divide-[var(--color-border)]">
            @foreach($recentGuests as $guest)
            <div class="flex items-center justify-between px-6 py-3">
                <div>
                    <p class="text-sm font-medium">{{ $guest->full_name }}</p>
                    <p class="text-xs" style="color: var(--color-muted);">{{ $guest->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5
                        {{ $guest->attending === 'yes' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                        {{ $guest->attending === 'yes' ? 'Attending' : 'Not Attending' }}
                    </span>
                    <span class="text-xs" style="color: var(--color-muted);">{{ $guest->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
