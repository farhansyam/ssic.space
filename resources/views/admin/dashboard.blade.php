<x-layouts.admin title="Dashboard">
    <div class="mb-6">
        <h2 class="font-display text-2xl font-semibold text-slate-800">Halo, {{ auth()->user()->name }} 👋</h2>
        <p class="mt-1 text-sm text-slate-500">Ini ringkasan singkat aktivitas SSIC hari ini.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Total Member</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['members'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Member terdaftar aktif</p>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-700 to-primary-800 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Total Divisi</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['divisions'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Divisi aktif di komunitas</p>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-900 to-primary-950 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Admin &amp; Super Admin</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['admins'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Pengelola panel admin</p>
        </div>

        <a href="{{ route('admin.kelas.index') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-400 to-primary-500 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Kelas Dibuka</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['classes_active'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Kelas menerima pendaftaran</p>
        </a>

        <a href="{{ route('admin.events.index') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Kegiatan Mendatang</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['events_upcoming'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Kegiatan yang akan berlangsung</p>
        </a>

        <a href="{{ route('admin.donation-campaigns.index') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-sunny-400 to-sunny-500 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Donasi Terkumpul</p>
            <p class="relative mt-1 font-display text-3xl font-bold">Rp{{ number_format($stats['donations_total'], 0, ',', '.') }}</p>
            <p class="relative mt-1 text-xs text-white/70">Total donasi terkonfirmasi</p>
        </a>

        <a href="{{ route('admin.posts.index') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-500 to-primary-800 p-6 text-white shadow-playful transition-transform duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
            <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10 transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            </div>
            <p class="relative mt-4 text-sm font-medium text-white/85">Artikel Terbit</p>
            <p class="relative mt-1 font-display text-4xl font-bold">{{ $stats['posts_published'] }}</p>
            <p class="relative mt-1 text-xs text-white/70">Artikel blog yang sudah publish</p>
        </a>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h3 class="font-display text-lg font-semibold text-slate-800">Tren Pendaftaran</h3>
                <p class="mt-0.5 text-sm text-slate-500">Total pendaftaran kelas &amp; kegiatan, 6 bulan terakhir.</p>
            </div>
        </div>

        @php
            $chartMax = max(1, max($chartValues));
            $chartWidth = 640;
            $chartHeight = 200;
            $barGap = 24;
            $barCount = count($chartValues);
            $barWidth = ($chartWidth - ($barGap * ($barCount + 1))) / $barCount;
        @endphp

        <div class="overflow-x-auto">
            <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight + 30 }}" class="w-full min-w-[480px]" style="height: 230px;">
                @foreach (range(0, 3) as $g)
                    <line x1="0" x2="{{ $chartWidth }}" y1="{{ $chartHeight - ($g * $chartHeight / 3) }}" y2="{{ $chartHeight - ($g * $chartHeight / 3) }}" stroke="#f1f5f9" stroke-width="1" />
                @endforeach

                @foreach ($chartValues as $i => $value)
                    @php
                        $barHeight = $value > 0 ? max(4, ($value / $chartMax) * ($chartHeight - 10)) : 0;
                        $x = $barGap + $i * ($barWidth + $barGap);
                        $y = $chartHeight - $barHeight;
                    @endphp
                    <g class="group">
                        <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barWidth }}" height="{{ $barHeight }}" rx="8" fill="url(#barGradient)" class="transition-opacity duration-200 hover:opacity-80">
                            <title>{{ $chartLabels[$i] }}: {{ $value }} pendaftaran</title>
                        </rect>
                        <text x="{{ $x + $barWidth / 2 }}" y="{{ $y - 8 }}" text-anchor="middle" class="fill-slate-500" style="font-size: 12px; font-weight: 600;">{{ $value }}</text>
                        <text x="{{ $x + $barWidth / 2 }}" y="{{ $chartHeight + 20 }}" text-anchor="middle" class="fill-slate-400" style="font-size: 12px;">{{ $chartLabels[$i] }}</text>
                    </g>
                @endforeach

                <defs>
                    <linearGradient id="barGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--color-primary-500, #2578da)" />
                        <stop offset="100%" stop-color="var(--color-primary-700, #1d5ca6)" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    @if ($stats['donations_pending'] > 0)
        <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}" class="mt-6 flex items-center gap-4 rounded-2xl border border-sunny-200 bg-sunny-50 p-5 transition hover:-translate-y-0.5 hover:shadow-playful">
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-sunny-400 text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            </div>
            <div class="flex-1">
                <p class="font-display font-semibold text-slate-800">{{ $stats['donations_pending'] }} donasi menunggu verifikasi</p>
                <p class="text-sm text-slate-500">Cek bukti transfer dan konfirmasi donasi yang masuk.</p>
            </div>
            <svg class="h-5 w-5 text-sunny-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
        </a>
    @endif
</x-layouts.admin>
