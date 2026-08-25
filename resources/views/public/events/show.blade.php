<x-layouts.public
    :title="$event->seoMeta?->meta_title ?? $event->title"
    :description="$event->seoMeta?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($event->description), 160)"
    :image="$event->seoMeta?->og_image ?? $event->image"
    type="article"
>
    <script type="application/ld+json">
        {!! json_encode([
            '@@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->title,
            'description' => $event->seoMeta?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($event->description), 160),
            'startDate' => $event->event_date->toDateString(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                '@type' => 'Place',
                'name' => $event->location ?: 'SSIC',
            ],
            'image' => $event->image ? asset(\Illuminate\Support\Facades\Storage::url($event->image)) : null,
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'SSIC',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <div
        x-data="{ lightbox: false, activeImage: '' }"
        @keydown.escape.window="lightbox = false"
        class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8"
    >
        <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke daftar kegiatan
        </a>

        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="relative w-full overflow-hidden bg-gradient-to-br from-cokelat-500 to-primary-700">
                @if ($event->image)
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="max-h-[70vh] w-full object-cover" style="object-position: {{ $event->image_focal_x }}% {{ $event->image_focal_y }}%">
                @else
                    <div class="h-56 sm:h-72"></div>
                @endif
            </div>

            <div class="p-6 sm:p-8">
                <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700">{{ $event->status === 'upcoming' ? 'Akan Datang' : 'Selesai' }}</span>
                <h1 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">{{ $event->title }}</h1>
                <p class="mt-3 whitespace-pre-line text-slate-600">{{ $event->description }}</p>

                <div class="mt-6 grid gap-4 rounded-2xl bg-slate-50 p-5 sm:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Tanggal</p>
                            <p class="text-sm font-medium text-slate-700">{{ $event->event_date->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Lokasi</p>
                            <p class="text-sm font-medium text-slate-700">{{ $event->location ?: 'Akan diinfokan' }}</p>
                        </div>
                    </div>
                </div>

                @if ($event->pjWhatsappLink())
                    <a href="{{ $event->pjWhatsappLink() }}" target="_blank" rel="noopener" class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-emerald-500 text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.001 2c-5.523 0-10 4.477-10 10 0 1.77.463 3.5 1.34 5.02L2 22l5.11-1.34A9.96 9.96 0 0012 22c5.523 0 10-4.477 10-10S17.524 2 12.001 2zm5.802 14.163c-.242.68-1.4 1.33-1.933 1.4-.494.066-1.11.094-1.789-.113-.412-.126-.94-.293-1.62-.575-2.85-1.23-4.71-4.1-4.85-4.29-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.377c.26-.288.567-.36.756-.36.19 0 .378.002.543.01.174.008.408-.066.638.487.242.578.822 1.997.894 2.142.07.144.117.313.023.503-.093.19-.14.31-.28.478-.14.168-.294.375-.42.504-.14.144-.286.3-.123.588.164.29.73 1.204 1.567 1.95 1.076.96 1.983 1.257 2.273 1.4.29.144.46.121.63-.073.17-.194.727-.85.922-1.142.194-.29.388-.242.654-.145.266.097 1.685.795 1.974.94.29.144.483.216.554.337.07.121.07.7-.172 1.38z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-emerald-800">Tanya PJ Kegiatan via WhatsApp</p>
                            <p class="text-xs text-emerald-600">{{ $event->pj_name ?: 'Penanggung Jawab' }}{{ $event->pj_name ? ' · ' : '' }}Chat langsung, respons lebih cepat.</p>
                        </div>
                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                @endif

                <div class="mt-8 border-t border-slate-100 pt-6">
                    @auth
                        @if ($event->isRegisteredBy(auth()->user()))
                            <div class="flex items-center gap-2 rounded-xl bg-primary-50 px-4 py-3 text-sm font-medium text-primary-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Kamu sudah terdaftar di kegiatan ini.
                            </div>
                        @elseif ($event->status !== 'upcoming')
                            <div class="rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-medium text-slate-500">Pendaftaran kegiatan ini sudah ditutup.</div>
                        @elseif ($event->registrationForm)
                            <a href="{{ route('form.show', $event->registrationForm) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg sm:w-auto">
                                Isi Form Pendaftaran
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </a>
                        @else
                            <form method="POST" action="{{ route('kegiatan.register', $event) }}" x-data="{ submitting: false }" @submit="submitting = true">
                                @csrf
                                <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:opacity-60 sm:w-auto">
                                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span x-text="submitting ? 'Memproses...' : 'Daftar Kegiatan Ini'"></span>
                                </button>
                            </form>
                        @endif
                    @elseif ($event->status !== 'upcoming')
                        <div class="rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-medium text-slate-500">Pendaftaran kegiatan ini sudah ditutup.</div>
                    @elseif ($event->registrationForm)
                        <a href="{{ route('form.show', $event->registrationForm) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg sm:w-auto">
                            Isi Form Pendaftaran
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @elseif ($event->allowsGuestRegistration())
                        <div x-data="{ createAccount: false, submitting: false }" class="rounded-2xl bg-slate-50 p-5">
                            <p class="text-sm font-semibold text-slate-700">Daftar Kegiatan Ini</p>
                            <p class="mt-0.5 text-xs text-slate-400">Kegiatan ini terbuka untuk umum — kamu bisa daftar tanpa akun.</p>
                            <form method="POST" action="{{ route('kegiatan.register', $event) }}" @submit="submitting = true" class="mt-4 space-y-3">
                                @csrf
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required maxlength="150" placeholder="Nama Lengkap" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                                        @error('guest_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" required maxlength="150" placeholder="Email" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                                        @error('guest_email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" maxlength="20" placeholder="No. HP (opsional)" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">

                                <label class="flex cursor-pointer items-center gap-2.5">
                                    <input type="checkbox" name="create_account" value="1" x-model="createAccount" class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
                                    <span class="text-sm text-slate-600">Sekalian buat akun (biar bisa klaim sertifikat nanti)</span>
                                </label>

                                <div x-show="createAccount" x-cloak class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <input type="password" name="password" minlength="8" :required="createAccount" placeholder="Password" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                                        @error('password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                    </div>
                                    <input type="password" name="password_confirmation" minlength="8" :required="createAccount" placeholder="Konfirmasi Password" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                                </div>

                                <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:opacity-60 sm:w-auto">
                                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span x-text="submitting ? 'Memproses...' : 'Daftar Sekarang'"></span>
                                </button>
                            </form>
                            <p class="mt-3 text-xs text-slate-400">Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">Masuk di sini</a> biar otomatis tercatat.</p>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Masuk untuk Daftar
                        </a>
                    @endauth
                </div>

                @if ($event->galleries->isNotEmpty())
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h2 class="font-display text-lg font-semibold text-slate-800">Galeri Dokumentasi</h2>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($event->galleries as $photo)
                                <button
                                    type="button"
                                    @click="lightbox = true; activeImage = '{{ Storage::url($photo->image_path) }}'"
                                    class="group aspect-square overflow-hidden rounded-xl bg-slate-100"
                                >
                                    <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->caption }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div
            x-show="lightbox" x-cloak
            x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            @click="lightbox = false"
            class="fixed inset-0 z-50 grid place-items-center bg-slate-900/80 p-6"
        >
            <img :src="activeImage" class="max-h-[85vh] max-w-full rounded-2xl object-contain shadow-playful-lg" @click.stop>
            <button @click="lightbox = false" class="absolute right-6 top-6 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
</x-layouts.public>
