@php
    $orgName = site_setting('org_name', 'SSIC');
    $orgLogo = site_setting('logo');
    $orgFavicon = site_setting('favicon');
    $themeColor = site_setting('theme_color');

    $seoTitle = ($title ?? 'Beranda').' · '.$orgName.' — Synergy Social Impact Community';
    $seoDescription = $description ?? 'Synergy Social Impact Community — komunitas sosial yang menyediakan kelas, kegiatan, dan donasi untuk bareng-bareng bikin dampak sosial yang nyata.';
    $seoImage = isset($image) && $image ? (str_starts_with($image, 'http') ? $image : asset(\Illuminate\Support\Facades\Storage::url($image))) : null;
    $seoType = $type ?? 'website';
    $seoCanonical = $canonical ?? url()->current();

    $darken = function (string $hex, float $factor = 0.82): string {
        $hex = ltrim($hex, '#');
        [$r, $g, $b] = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];

        return sprintf('#%02x%02x%02x', (int) ($r * $factor), (int) ($g * $factor), (int) ($b * $factor));
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    @if (! empty($noindex))
        <meta name="robots" content="noindex, nofollow">
    @endif
    @if ($orgFavicon)
        <link rel="icon" href="{{ Illuminate\Support\Facades\Storage::url($orgFavicon) }}">
    @endif

    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="{{ $themeColor ?: '#2474D2' }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $orgName }}">

    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:site_name" content="{{ $orgName }}">
    <meta property="og:title" content="{{ $title ?? $orgName }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    @if ($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
    @endif
    <meta property="og:url" content="{{ $seoCanonical }}">

    <meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $title ?? $orgName }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if ($seoImage)
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if ($themeColor)
        <style>
            :root {
                --color-primary-500: {{ $themeColor }};
                --color-primary-600: {{ $darken($themeColor) }};
            }
        </style>
    @endif
</head>
<body class="flex min-h-screen flex-col bg-slate-50 antialiased" data-pixel-transition="true">
    <x-flash-messages />
    <x-popup-modal />

    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-40 border-b border-slate-100 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center gap-6 px-4 py-3.5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                @if ($orgLogo)
                    <img src="{{ Illuminate\Support\Facades\Storage::url($orgLogo) }}" alt="{{ $orgName }}" class="h-10 w-10 rounded-full object-cover shadow-playful">
                @else
                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 font-display text-base font-bold text-white shadow-playful">{{ Str::of($orgName)->substr(0, 1)->upper() }}</div>
                @endif
                <span class="font-display text-lg font-semibold text-slate-800">{{ $orgName }}</span>
            </a>

            <nav class="hidden items-center gap-1 md:flex">
                @php
                    $navLinks = [
                        ['route' => 'home', 'label' => 'Beranda', 'match' => 'home'],
                        ['route' => 'kelas.index', 'label' => 'Kelas', 'match' => 'kelas.*'],
                        ['route' => 'kegiatan.index', 'label' => 'Kegiatan', 'match' => 'kegiatan.*'],
                        ['route' => 'donasi.index', 'label' => 'Donasi', 'match' => 'donasi.*'],
                        ['route' => 'blog.index', 'label' => 'Blog', 'match' => 'blog.*'],
                        ['route' => 'recruitment', 'label' => 'Recruitment', 'match' => 'recruitment'],
                    ];
                @endphp
                @foreach ($navLinks as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="rounded-lg px-3.5 py-2 text-sm font-medium transition-colors {{ request()->routeIs($link['match']) ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >{{ $link['label'] }}</a>
                @endforeach
            </nav>

            <div class="ml-auto hidden items-center gap-3 md:flex">
                @auth
                    <a href="{{ route('profile.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">Profil Saya</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Keluar ({{ Str::of(auth()->user()->name)->before(' ') }})
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:text-primary-600">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">Daftar</a>
                @endauth
            </div>

            <button @click="mobileOpen = !mobileOpen" class="ml-auto rounded-lg p-2 text-slate-500 hover:bg-slate-100 md:hidden">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div
            x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="border-t border-slate-100 bg-white px-4 py-3 md:hidden"
        >
            <nav class="flex flex-col gap-1">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="rounded-lg px-3.5 py-2.5 text-sm font-medium {{ request()->routeIs($link['match']) ? 'bg-primary-50 text-primary-700' : 'text-slate-600' }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>
            <div class="mt-3 flex flex-col gap-2 border-t border-slate-100 pt-3">
                @auth
                    <a href="{{ route('profile.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-600">Profil Saya</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-600">Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-600">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-center text-sm font-semibold text-white">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="border-t border-slate-100 bg-white">
        <div
            x-data="{
                email: '', phone: '', submitting: false, done: false, error: false,
                get emailValid() { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email); },
                async subscribe() {
                    if (!this.emailValid) { this.error = true; return; }
                    this.submitting = true; this.error = false;
                    const res = await fetch('{{ route('newsletter.subscribe') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ email: this.email, phone: this.phone }),
                    });
                    this.submitting = false;
                    if (res.ok) { this.done = true; this.email = ''; this.phone = ''; } else { this.error = true; }
                },
            }"
            class="border-b border-slate-100 bg-gradient-to-br from-primary-500 to-primary-700"
        >
            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-5 text-center sm:flex-row sm:justify-between sm:text-left">
                    <div>
                        <p class="font-display text-lg font-semibold text-white">Newsletter &amp; WA Update</p>
                        <p class="mt-1 text-sm text-primary-100">Dapatkan kabar kelas, kegiatan, dan campaign donasi terbaru.</p>
                    </div>

                    <div class="w-full max-w-md">
                        <template x-if="!done">
                            <form @submit.prevent="subscribe()" class="flex flex-col gap-2.5 sm:flex-row">
                                <div class="flex-1">
                                    <input
                                        type="email" x-model="email" placeholder="emailmu@contoh.com" required
                                        class="w-full rounded-xl border-0 bg-white/95 px-4 py-2.5 text-sm text-slate-800 outline-none ring-2 ring-transparent transition placeholder:text-slate-400 focus:ring-white"
                                        :class="error && !emailValid ? 'ring-2 ring-rose-400' : ''"
                                    >
                                    <p x-show="error && !emailValid" x-cloak class="mt-1 text-left text-xs text-rose-100">Masukkan email yang valid ya.</p>
                                </div>
                                <button type="submit" :disabled="submitting" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sunny-400 px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-playful transition-all hover:-translate-y-0.5 hover:bg-sunny-300 disabled:cursor-not-allowed disabled:opacity-60">
                                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span x-text="submitting ? 'Mengirim...' : 'Subscribe'"></span>
                                </button>
                            </form>
                        </template>
                        <template x-if="done">
                            <p x-transition class="flex items-center justify-center gap-2 text-sm font-medium text-white sm:justify-start">
                                <svg class="h-5 w-5 text-sunny-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Berhasil subscribe! Terima kasih.
                            </p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1.2fr]">
                <div>
                    <div class="flex items-center gap-2.5">
                        @if ($orgLogo)
                            <img src="{{ Illuminate\Support\Facades\Storage::url($orgLogo) }}" alt="{{ $orgName }}" class="h-9 w-9 rounded-full object-cover">
                        @else
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 font-display text-sm font-bold text-white">{{ Str::of($orgName)->substr(0, 1)->upper() }}</div>
                        @endif
                        <span class="font-display text-base font-semibold text-slate-800">{{ $orgName }}</span>
                    </div>
                    <p class="mt-3.5 max-w-xs text-sm leading-relaxed text-slate-500">{{ site_setting('org_description', 'Bareng-bareng bikin dampak sosial yang nyata lewat kelas, kegiatan, dan donasi.') }}</p>
                    @if (site_setting('org_hashtag_tagline', '#Reform #Transform #Perform'))
                        <p class="mt-2 font-display text-xs font-semibold uppercase tracking-[0.15em] text-primary-500">{{ site_setting('org_hashtag_tagline', '#Reform #Transform #Perform') }}</p>
                    @endif

                    @if (site_setting('social_instagram') || site_setting('social_facebook') || site_setting('social_whatsapp'))
                        <div class="mt-5 flex items-center gap-3">
                            @if (site_setting('social_instagram'))
                                <a href="{{ site_setting('social_instagram') }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-primary-100 hover:text-primary-600">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="18" height="18" rx="5" /><circle cx="12" cy="12" r="3.5" /><circle cx="17.2" cy="6.8" r="0.6" fill="currentColor" /></svg>
                                </a>
                            @endif
                            @if (site_setting('social_facebook'))
                                <a href="{{ site_setting('social_facebook') }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-primary-100 hover:text-primary-600">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M14 9h3V6h-3a4 4 0 00-4 4v2H7v3h3v6h3v-6h3l1-3h-4v-2a1 1 0 011-1z" /></svg>
                                </a>
                            @endif
                            @if (site_setting('social_whatsapp'))
                                <a href="{{ site_setting('social_whatsapp') }}" target="_blank" rel="noopener" class="grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-primary-100 hover:text-primary-600">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.284a2.25 2.25 0 01-1.7 2.182l-2.05.513-1.5 2.663-1.5-2.25h-3.75a2.25 2.25 0 01-2.25-2.25v-.75M3.75 15h1.5m9-9v.008h-.008V6H14.25zm-2.25 0v.008h-.008V6H12zm-2.25 0v.008h-.008V6H9.75zM6.75 12a4.5 4.5 0 004.5 4.5H14.5l2.25 2.25V16.5A4.5 4.5 0 0021 12V7.5A4.5 4.5 0 0016.5 3h-6a4.5 4.5 0 00-4.5 4.5v.5" /></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <p class="font-display text-sm font-semibold text-slate-800">Navigasi</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-500">
                        <li><a href="{{ route('home') }}" class="transition hover:text-primary-600">Beranda</a></li>
                        <li><a href="{{ route('kelas.index') }}" class="transition hover:text-primary-600">Kelas</a></li>
                        <li><a href="{{ route('kegiatan.index') }}" class="transition hover:text-primary-600">Kegiatan</a></li>
                        <li><a href="{{ route('donasi.index') }}" class="transition hover:text-primary-600">Donasi</a></li>
                        <li><a href="{{ route('blog.index') }}" class="transition hover:text-primary-600">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-display text-sm font-semibold text-slate-800">Akun</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-500">
                        @auth
                            <li><a href="{{ route('profile.index') }}" class="transition hover:text-primary-600">Profil Saya</a></li>
                            @if (auth()->user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}" class="transition hover:text-primary-600">Admin Panel</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="transition hover:text-primary-600">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="transition hover:text-primary-600">Daftar</a></li>
                        @endauth
                        <li><a href="{{ route('recruitment') }}" class="transition hover:text-primary-600">Open Recruitment</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-display text-sm font-semibold text-slate-800">Kontak Kami</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        @if (site_setting('contact_email'))
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                <a href="mailto:{{ site_setting('contact_email') }}" class="transition hover:text-primary-600">{{ site_setting('contact_email') }}</a>
                            </li>
                        @endif
                        @if (site_setting('contact_phone'))
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293a11.25 11.25 0 01-6.16-6.16l1.293-.97a1.125 1.125 0 00.417-1.173L8.963 3.102a1.125 1.125 0 00-1.091-.852H6.5A2.25 2.25 0 004.25 4.5v2.25z" /></svg>
                                <a href="tel:{{ site_setting('contact_phone') }}" class="transition hover:text-primary-600">{{ site_setting('contact_phone') }}</a>
                            </li>
                        @endif
                        @if (site_setting('contact_address'))
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                <span>{{ site_setting('contact_address') }}</span>
                            </li>
                        @endif
                        @if (! site_setting('contact_email') && ! site_setting('contact_phone') && ! site_setting('contact_address'))
                            <li class="text-slate-400">Belum ada info kontak.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                <p class="text-center text-sm text-slate-400">&copy; {{ date('Y') }} {{ $orgName }}. Bareng-bareng bikin dampak.</p>
                <p class="text-center text-xs text-slate-400">Dibuat dengan &hearts; untuk komunitas.</p>
            </div>
        </div>
    </footer>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
