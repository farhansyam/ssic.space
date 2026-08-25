@php
    $orgName = site_setting('org_name', 'SSIC Space');
    $orgTagline = site_setting('org_tagline', 'Synergy Social Impact Community');
    $orgDescription = site_setting('org_description', 'bareng-bareng bikin dampak sosial yang nyata lewat kelas, kegiatan, dan donasi.');
    $orgLogo = site_setting('logo');
    $heroImage = site_setting('hero_image');
    $hashtagTagline = site_setting('org_hashtag_tagline', '#Reform #Transform #Perform');
@endphp
<x-layouts.public
    title="Beranda"
    :description="$orgTagline.' — '.$orgDescription"
>
    <script type="application/ld+json">
        {!! json_encode([
            '@@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $orgTagline,
            'alternateName' => $orgName,
            'url' => route('home'),
            'description' => $orgDescription,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    @if ($heroBanners->isNotEmpty())
        <x-hero-carousel :banners="$heroBanners" />
    @else
        <div class="relative overflow-hidden px-4 py-20 text-center sm:px-6 lg:px-8">
            @if ($heroImage)
                <div class="absolute inset-0">
                    <img src="{{ Illuminate\Support\Facades\Storage::url($heroImage) }}" alt="{{ $orgName }}" class="h-full w-full object-cover opacity-20">
                </div>
            @endif
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-primary-300/40 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-16 h-96 w-96 rounded-full bg-cokelat-200/40 blur-3xl"></div>
                <div class="absolute right-1/3 top-1/4 h-48 w-48 rounded-full bg-primary-200/40 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-2xl">
                @if ($orgLogo)
                    <img src="{{ Illuminate\Support\Facades\Storage::url($orgLogo) }}" alt="{{ $orgName }}" class="mx-auto h-16 w-16 rounded-full object-cover shadow-playful-lg">
                @else
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-gradient-to-br from-primary-400 to-primary-600 font-display text-2xl font-bold text-white shadow-playful-lg">{{ Str::of($orgName)->substr(0, 1)->upper() }}</div>
                @endif
                <h1 class="mt-6 font-display text-4xl font-bold text-slate-800 sm:text-5xl">{{ $orgName }}</h1>
                <p class="mx-auto mt-3 max-w-md text-slate-500">{{ $orgTagline }} — {{ $orgDescription }}</p>
                @if ($hashtagTagline)
                    <p class="mt-3 font-display text-xs font-semibold uppercase tracking-[0.2em] text-primary-500">{{ $hashtagTagline }}</p>
                @endif

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Gabung Sekarang
                        </a>
                        <a href="{{ route('kegiatan.index') }}" class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition-all hover:-translate-y-0.5 hover:border-primary-300 hover:text-primary-600">
                            Lihat Kegiatan
                        </a>
                    @else
                        <a href="{{ route('kelas.index') }}" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Jelajahi Kelas
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    @endif

    <!-- Stats -->
    <div
        x-data="{
            counted: false,
            counters: { members: 0, events: 0, classes: 0 },
            targets: { members: {{ (int) $stats['members'] }}, events: {{ (int) $stats['events'] }}, classes: {{ (int) $stats['classes'] }} },
            init() {
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting && !this.counted) {
                        this.counted = true;
                        this.runCount();
                    }
                }, { threshold: 0.4 });
                observer.observe(this.$el);
            },
            runCount() {
                const duration = 1200;
                const start = performance.now();
                const step = (now) => {
                    const progress = Math.min(1, (now - start) / duration);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    for (const key in this.targets) {
                        this.counters[key] = Math.round(this.targets[key] * eased);
                    }
                    if (progress < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            },
        }"
        class="relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-700 py-14"
    >
        <div class="pointer-events-none absolute inset-0 opacity-10">
            <div class="absolute -left-10 top-0 h-56 w-56 rounded-full bg-white blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-64 w-64 rounded-full bg-white blur-3xl"></div>
        </div>
        <div class="relative mx-auto grid max-w-4xl grid-cols-3 gap-6 px-4 text-center sm:px-6 lg:px-8">
            <div>
                <p class="font-display text-3xl font-bold text-white sm:text-4xl" x-text="counters.members + '+'"></p>
                <p class="mt-1 text-sm text-primary-100">Anggota Terdaftar</p>
            </div>
            <div>
                <p class="font-display text-3xl font-bold text-white sm:text-4xl" x-text="counters.events + '+'"></p>
                <p class="mt-1 text-sm text-primary-100">Kegiatan Terlaksana</p>
            </div>
            <div>
                <p class="font-display text-3xl font-bold text-white sm:text-4xl" x-text="counters.classes + '+'"></p>
                <p class="mt-1 text-sm text-primary-100">Kelas Aktif</p>
            </div>
        </div>
    </div>

    <!-- Programs -->
    <div class="relative overflow-hidden px-4 py-20 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 overflow-hidden opacity-60">
            <div class="absolute -left-16 top-10 h-40 w-40 rounded-full bg-sunny-200/50 blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-48 w-48 rounded-full bg-cokelat-200/40 blur-3xl"></div>
        </div>
        <div class="relative mx-auto max-w-6xl">
            <div x-reveal class="mx-auto max-w-xl text-center">
                <span class="inline-block rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary-700">Yang Bisa Kamu Lakukan</span>
                <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Ikut Terlibat Bareng Kami</h2>
                <p class="mt-2 text-sm text-slate-500">Tiga cara gampang buat mulai bikin dampak sosial bareng {{ $orgName }}.</p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-3">
                <a x-reveal href="{{ route('kelas.index') }}" class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-primary-100 text-primary-600 transition-colors group-hover:bg-primary-500 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-slate-800">Kelas &amp; Program</h3>
                    <p class="mt-1 text-sm text-slate-500">Ikut kelas belajar gratis maupun berbayar bareng komunitas.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        Jelajahi <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </span>
                </a>

                <a x-reveal.100 href="{{ route('kegiatan.index') }}" class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-cokelat-100 text-cokelat-700 transition-colors group-hover:bg-cokelat-600 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-slate-800">Kegiatan Komunitas</h3>
                    <p class="mt-1 text-sm text-slate-500">Ikut kegiatan sosial dan lihat dokumentasi keseruan sebelumnya.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-cokelat-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        Jelajahi <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </span>
                </a>

                <a x-reveal.200 href="{{ route('donasi.index') }}" class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-sunny-100 text-sunny-700 transition-colors group-hover:bg-sunny-400 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    </div>
                    <h3 class="mt-4 font-display text-lg font-semibold text-slate-800">Donasi &amp; Fundraising</h3>
                    <p class="mt-1 text-sm text-slate-500">Salurkan bantuan lewat campaign donasi yang transparan.</p>
                    <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-sunny-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        Jelajahi <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </span>
                </a>
            </div>
        </div>
    </div>

    @if ($divisions->isNotEmpty())
        <div class="bg-slate-50 py-16">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,320px)_1fr] lg:px-8">
                <div x-reveal>
                    <span class="inline-block rounded-full bg-cokelat-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cokelat-700">Divisi</span>
                    <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Digerakkan oleh Divisi-Divisi Kami</h2>
                    <p class="mt-3 text-sm text-slate-500">Setiap divisi punya fokus dan cara sendiri buat bikin dampak — dari sini komunitas {{ $orgName }} bergerak.</p>
                </div>

                <div x-reveal class="divide-y divide-slate-200 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    @foreach ($divisions as $index => $division)
                        <div class="group flex items-center gap-5 p-5 transition-colors duration-300 hover:bg-slate-50 sm:p-6">
                            <span class="font-display text-2xl font-bold text-slate-200 transition-colors duration-300 group-hover:text-primary-300">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if ($division->icon)
                                <img src="{{ Storage::url($division->icon) }}" alt="{{ $division->name }}" loading="lazy" class="h-14 w-14 shrink-0 rounded-2xl object-cover">
                            @else
                                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-cokelat-400 to-primary-500 font-display text-lg font-bold text-white">{{ Str::of($division->name)->substr(0, 1)->upper() }}</div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="font-display font-semibold text-slate-800">{{ $division->name }}</h3>
                                <p class="mt-0.5 line-clamp-1 text-sm text-slate-500">{{ $division->description ?: 'Belum ada deskripsi.' }}</p>
                            </div>
                            <span class="hidden shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 sm:inline-block">{{ $division->users_count }} anggota</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($kelasList->isNotEmpty())
        <div class="overflow-hidden bg-white py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <span class="inline-block rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary-700">Kelas</span>
                        <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Kelas &amp; Program Terbaru</h2>
                        <p class="mt-2 text-sm text-slate-500">Geser buat lihat pilihan kelas lainnya.</p>
                    </div>
                    <a href="{{ route('kelas.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">
                        Lihat Semua <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
            <div x-reveal class="mt-8 flex snap-x snap-mandatory gap-5 overflow-x-auto px-4 pb-4 sm:px-6 lg:px-[max(1.5rem,calc((100vw-72rem)/2+1.5rem))] [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ($kelasList as $kelas)
                    <a href="{{ route('kelas.show', $kelas) }}" class="group w-72 shrink-0 snap-start overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-44 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($kelas->image)
                                <img src="{{ Storage::url($kelas->image) }}" alt="{{ $kelas->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $kelas->image_focal_x }}% {{ $kelas->image_focal_y }}%">
                            @endif
                            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold capitalize text-slate-700">{{ $kelas->category }}</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $kelas->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $kelas->description }}</p>
                        </div>
                    </a>
                @endforeach
                <div class="w-px shrink-0"></div>
            </div>
        </div>
    @endif

    @if ($eventList->isNotEmpty())
        <div class="bg-slate-50 py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="text-center">
                    <span class="inline-block rounded-full bg-cokelat-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cokelat-700">Kegiatan</span>
                    <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Kegiatan Akan Datang</h2>
                    <p class="mt-2 text-sm text-slate-500">Catat tanggalnya, jangan sampai ketinggalan.</p>
                </div>

                <div class="relative mt-12">
                    <div class="absolute bottom-0 left-[27px] top-0 w-px bg-slate-200 sm:left-[35px]"></div>
                    <div class="space-y-8">
                        @foreach ($eventList as $event)
                            <a x-reveal href="{{ route('kegiatan.show', $event) }}" class="group relative flex items-start gap-5 sm:gap-6">
                                <div class="relative z-10 grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-white shadow-sm ring-4 ring-slate-50 transition-colors duration-300 group-hover:bg-cokelat-600 sm:h-[70px] sm:w-[70px]">
                                    <div class="text-center">
                                        <p class="font-display text-lg font-bold leading-none text-cokelat-700 transition-colors duration-300 group-hover:text-white sm:text-xl">{{ $event->event_date->format('d') }}</p>
                                        <p class="mt-0.5 text-[10px] font-semibold uppercase leading-none text-slate-400 transition-colors duration-300 group-hover:text-white/80">{{ $event->event_date->translatedFormat('M') }}</p>
                                    </div>
                                </div>
                                <div class="flex-1 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-playful-lg sm:flex sm:items-stretch">
                                    <div class="relative h-32 shrink-0 overflow-hidden bg-gradient-to-br from-cokelat-500 to-primary-700 sm:h-auto sm:w-40">
                                        @if ($event->image)
                                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $event->image_focal_x }}% {{ $event->image_focal_y }}%">
                                        @endif
                                    </div>
                                    <div class="flex-1 p-4">
                                        <h3 class="font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $event->title }}</h3>
                                        <p class="mt-1 flex items-center gap-1.5 text-xs text-slate-400">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                            {{ $event->location ?: 'Lokasi menyusul' }}
                                        </p>
                                        <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $event->description }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div x-reveal class="mt-10 text-center">
                    <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cokelat-700 hover:text-cokelat-800">
                        Lihat Semua Kegiatan <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if ($campaignList->isNotEmpty())
        @php $featuredCampaign = $campaignList->first(); $otherCampaigns = $campaignList->slice(1); @endphp
        <div class="bg-white py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <span class="inline-block rounded-full bg-sunny-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-sunny-700">Donasi</span>
                        <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Campaign Donasi</h2>
                    </div>
                    <a href="{{ route('donasi.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-sunny-700 hover:text-sunny-800">
                        Lihat Semua <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-5">
                    <a x-reveal href="{{ route('donasi.show', $featuredCampaign) }}" class="group relative overflow-hidden rounded-3xl shadow-playful-lg lg:col-span-3">
                        <div class="relative h-72 w-full overflow-hidden bg-gradient-to-br from-sunny-500 to-cokelat-700 sm:h-96">
                            @if ($featuredCampaign->image)
                                <img src="{{ Storage::url($featuredCampaign->image) }}" alt="{{ $featuredCampaign->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent"></div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            <span class="inline-block rounded-full bg-sunny-400 px-2.5 py-1 text-xs font-semibold text-slate-900">Campaign Utama</span>
                            <h3 class="mt-3 font-display text-xl font-bold text-white sm:text-2xl">{{ $featuredCampaign->title }}</h3>
                            <div class="mt-4 max-w-sm">
                                <div class="flex items-center justify-between text-sm text-white">
                                    <span class="font-semibold">Rp{{ number_format($featuredCampaign->collectedAmount(), 0, ',', '.') }}</span>
                                    <span class="text-white/70">dari Rp{{ number_format($featuredCampaign->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-white/20">
                                    <div class="h-full rounded-full bg-gradient-to-r from-sunny-400 to-sunny-300 transition-all duration-700" style="width: {{ $featuredCampaign->progressPercent() }}%"></div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="flex flex-col gap-4 lg:col-span-2">
                        @forelse ($otherCampaigns as $campaign)
                            <a x-reveal href="{{ route('donasi.show', $campaign) }}" class="group flex flex-1 items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-primary-400 to-primary-600">
                                    @if ($campaign->image)
                                        <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate font-display text-sm font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $campaign->title }}</h3>
                                    <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-primary-600" style="width: {{ $campaign->progressPercent() }}%"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">{{ $campaign->progressPercent() }}% terkumpul</p>
                                </div>
                            </a>
                        @empty
                            <a x-reveal href="{{ route('donasi.umum') }}" class="flex flex-1 flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 p-6 text-center transition hover:border-sunny-300 hover:bg-sunny-50">
                                <svg class="h-8 w-8 text-sunny-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                <p class="mt-2 text-sm font-semibold text-slate-600">Mau donasi tanpa campaign?</p>
                                <p class="mt-1 text-xs text-slate-400">Coba Donasi Umum di sini.</p>
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($postList->isNotEmpty())
        @php $featuredPost = $postList->first(); $otherPosts = $postList->slice(1); @endphp
        <div class="bg-slate-50 py-16">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <span class="inline-block rounded-full bg-cokelat-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cokelat-700">Blog</span>
                        <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Artikel Terbaru</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cokelat-700 hover:text-cokelat-800">
                        Lihat Semua <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <a x-reveal href="{{ route('blog.show', $featuredPost) }}" class="group block overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-60 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600 sm:h-72">
                            @if ($featuredPost->featured_image)
                                <img src="{{ Storage::url($featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $featuredPost->image_focal_x }}% {{ $featuredPost->image_focal_y }}%">
                            @endif
                            @if ($featuredPost->category)
                                <span class="absolute left-4 top-4 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $featuredPost->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-xl font-bold text-slate-800 transition-colors group-hover:text-primary-600">{{ $featuredPost->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ Str::limit(strip_tags($featuredPost->content), 140) }}</p>
                            <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary-600">
                                Baca Selengkapnya <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </span>
                        </div>
                    </a>

                    <div class="flex flex-col divide-y divide-slate-200 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                        @forelse ($otherPosts as $post)
                            <a x-reveal href="{{ route('blog.show', $post) }}" class="group flex flex-1 items-center gap-4 p-4 transition-colors duration-300 hover:bg-slate-50 sm:p-5">
                                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-primary-400 to-primary-600">
                                    @if ($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" class="h-full w-full object-cover" style="object-position: {{ $post->image_focal_x }}% {{ $post->image_focal_y }}%">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    @if ($post->category)
                                        <span class="text-xs font-semibold uppercase tracking-wide text-cokelat-600">{{ $post->category->name }}</span>
                                    @endif
                                    <h3 class="font-display text-sm font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $post->title }}</h3>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-slate-300 transition-transform group-hover:translate-x-1 group-hover:text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                        @empty
                            <div class="flex flex-1 items-center justify-center p-8 text-center text-sm text-slate-400">Artikel lainnya nyusul, stay tuned!</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($testimonials->isNotEmpty())
        <div class="overflow-hidden bg-white py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="mx-auto max-w-xl text-center">
                    <span class="inline-block rounded-full bg-sunny-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-sunny-700">Testimoni</span>
                    <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Kata Mereka</h2>
                    <p class="mt-2 text-sm text-slate-500">Cerita dari anggota dan mitra komunitas kami.</p>
                </div>

                @if ($testimonials->count() > 3)
                    <div x-reveal class="mt-10 flex snap-x snap-mandatory gap-5 overflow-x-auto pb-4 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @foreach ($testimonials as $t)
                            <div class="flex w-80 shrink-0 snap-start flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                                <svg class="h-7 w-7 text-sunny-200" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/></svg>
                                <div class="mt-2 flex gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= $t->rating ? 'text-sunny-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                                    @endfor
                                </div>
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">&ldquo;{{ $t->content }}&rdquo;</p>
                                <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">
                                    <div class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-full bg-gradient-to-br from-primary-400 to-primary-600 font-display text-sm font-semibold text-white">
                                        @if ($t->photo)
                                            <img src="{{ Storage::url($t->photo) }}" alt="{{ $t->name }}" loading="lazy" class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-display text-sm font-semibold text-slate-800">{{ $t->name }}</p>
                                        <p class="truncate text-xs text-slate-400">{{ $t->role_or_status }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="w-px shrink-0"></div>
                    </div>
                @else
                    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($testimonials as $t)
                            <div x-reveal class="flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                                <svg class="h-7 w-7 text-sunny-200" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/></svg>
                                <div class="mt-2 flex gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= $t->rating ? 'text-sunny-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                                    @endfor
                                </div>
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">&ldquo;{{ $t->content }}&rdquo;</p>
                                <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">
                                    <div class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-full bg-gradient-to-br from-primary-400 to-primary-600 font-display text-sm font-semibold text-white">
                                        @if ($t->photo)
                                            <img src="{{ Storage::url($t->photo) }}" alt="{{ $t->name }}" loading="lazy" class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-display text-sm font-semibold text-slate-800">{{ $t->name }}</p>
                                        <p class="truncate text-xs text-slate-400">{{ $t->role_or_status }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($instagramPosts->isNotEmpty())
        <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
            <div x-reveal class="mx-auto max-w-xl text-center">
                <span class="inline-block rounded-full bg-cokelat-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cokelat-700">Galeri</span>
                <h2 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">Momen Kami di Instagram</h2>
                <p class="mt-2 text-sm text-slate-500">Ikuti keseruan kegiatan kami setiap harinya.</p>
                @if (site_setting('social_instagram'))
                    <a href="{{ site_setting('social_instagram') }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700">
                        @ssic.impact
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </a>
                @endif
            </div>
            <div x-reveal class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($instagramPosts as $post)
                    <a href="{{ $post->permalink ?? '#' }}" target="_blank" rel="noopener" class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
                        <img src="{{ str_starts_with($post->media_url, 'http') ? $post->media_url : Storage::url($post->media_url) }}" alt="{{ \Illuminate\Support\Str::limit($post->caption, 60) }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 flex items-end bg-slate-900/0 p-3 opacity-0 transition-all duration-300 group-hover:bg-slate-900/40 group-hover:opacity-100">
                            <p class="line-clamp-2 text-xs text-white">{{ $post->caption }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($partners->isNotEmpty())
        <div class="bg-white py-16">
            <div x-reveal class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs font-semibold uppercase tracking-wide text-slate-400">Didukung Oleh</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-x-12 gap-y-8">
                    @foreach ($partners as $partner)
                        <a href="{{ $partner->link ?? '#' }}" target="_blank" rel="noopener" class="grayscale transition-all duration-300 hover:grayscale-0" title="{{ $partner->name }}">
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" loading="lazy" class="h-16 max-w-[220px] object-contain sm:h-20">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Final CTA -->
    <div class="relative overflow-hidden bg-slate-50 px-4 py-20 sm:px-6 lg:px-8">
        <div x-reveal class="relative mx-auto max-w-4xl overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-cokelat-800 to-primary-900 px-6 py-14 text-center shadow-playful-lg sm:px-12">
            <div class="pointer-events-none absolute inset-0 overflow-hidden opacity-25">
                <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white blur-3xl"></div>
                <div class="absolute -right-10 -bottom-10 h-48 w-48 rounded-full bg-white blur-3xl"></div>
            </div>
            <div class="relative">
                <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Siap Bikin Dampak Bareng Kami?</h2>
                <p class="mx-auto mt-3 max-w-md text-sm text-white/85">Gabung jadi volunteer, ikut kelas, atau mulai donasi hari ini — setiap langkah kecil berarti buat sesama.</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="rounded-xl bg-white px-6 py-3 text-sm font-semibold text-primary-700 shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Gabung Sekarang
                        </a>
                    @endguest
                    <a href="{{ route('recruitment') }}" class="rounded-xl border border-white/40 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition-all hover:-translate-y-0.5 hover:bg-white/20">
                        Lihat Open Recruitment
                    </a>
                    <a href="{{ route('donasi.index') }}" class="rounded-xl border border-white/40 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition-all hover:-translate-y-0.5 hover:bg-white/20">
                        Mulai Donasi
                    </a>
                </div>
                @if ($hashtagTagline)
                    <p class="mt-8 font-display text-xs font-semibold uppercase tracking-[0.2em] text-white/60">{{ $hashtagTagline }}</p>
                @endif
            </div>
        </div>
    </div>

    @if (site_setting('contact_email') || site_setting('contact_phone') || site_setting('contact_address'))
        <div class="bg-white py-20">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div x-reveal class="overflow-hidden rounded-3xl bg-gradient-to-br from-primary-500 to-primary-700 px-6 py-12 text-center shadow-playful-lg sm:px-12">
                    <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Hubungi Kami</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm text-primary-100">Ada pertanyaan atau mau kolaborasi? Kontak kami lewat channel di bawah ini.</p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        @if (site_setting('contact_email'))
                            <a href="mailto:{{ site_setting('contact_email') }}" class="flex flex-col items-center gap-2 rounded-2xl bg-white/10 px-4 py-5 text-white transition hover:-translate-y-1 hover:bg-white/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                <span class="text-sm font-medium">{{ site_setting('contact_email') }}</span>
                            </a>
                        @endif
                        @if (site_setting('contact_phone'))
                            <a href="tel:{{ site_setting('contact_phone') }}" class="flex flex-col items-center gap-2 rounded-2xl bg-white/10 px-4 py-5 text-white transition hover:-translate-y-1 hover:bg-white/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293a11.25 11.25 0 01-6.16-6.16l1.293-.97a1.125 1.125 0 00.417-1.173L8.963 3.102a1.125 1.125 0 00-1.091-.852H6.5A2.25 2.25 0 004.25 4.5v2.25z" /></svg>
                                <span class="text-sm font-medium">{{ site_setting('contact_phone') }}</span>
                            </a>
                        @endif
                        @if (site_setting('contact_address'))
                            <div class="flex flex-col items-center gap-2 rounded-2xl bg-white/10 px-4 py-5 text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                <span class="text-sm font-medium">{{ site_setting('contact_address') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.public>
