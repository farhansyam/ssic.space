<x-layouts.public
    :title="$kelas->seoMeta?->meta_title ?? $kelas->title"
    :description="$kelas->seoMeta?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($kelas->description), 160)"
    :image="$kelas->seoMeta?->og_image ?? $kelas->image"
>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('kelas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke daftar kelas
        </a>

        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <div class="relative h-56 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600 sm:h-72">
                @if ($kelas->image)
                    <img src="{{ Storage::url($kelas->image) }}" alt="{{ $kelas->title }}" class="h-full w-full object-cover">
                @endif
            </div>

            <div class="p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold capitalize text-primary-700">{{ $kelas->category }}</span>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-500">{{ $kelas->level }}</span>
                </div>

                <h1 class="mt-3 font-display text-2xl font-bold text-slate-800 sm:text-3xl">{{ $kelas->title }}</h1>
                <p class="mt-3 whitespace-pre-line text-slate-600">{{ $kelas->description }}</p>

                <div class="mt-6 grid gap-4 rounded-2xl bg-slate-50 p-5 sm:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Jadwal</p>
                            <p class="text-sm font-medium text-slate-700">{{ $kelas->schedule ?: 'Akan diinfokan' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        <div>
                            <p class="text-xs font-medium text-slate-400">Lokasi</p>
                            <p class="text-sm font-medium text-slate-700">{{ $kelas->location ?: 'Akan diinfokan' }}</p>
                        </div>
                    </div>
                </div>

                @if ($kelas->pjWhatsappLink())
                    <a href="{{ $kelas->pjWhatsappLink() }}" target="_blank" rel="noopener" class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 transition hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-emerald-500 text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.001 2c-5.523 0-10 4.477-10 10 0 1.77.463 3.5 1.34 5.02L2 22l5.11-1.34A9.96 9.96 0 0012 22c5.523 0 10-4.477 10-10S17.524 2 12.001 2zm5.802 14.163c-.242.68-1.4 1.33-1.933 1.4-.494.066-1.11.094-1.789-.113-.412-.126-.94-.293-1.62-.575-2.85-1.23-4.71-4.1-4.85-4.29-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.377c.26-.288.567-.36.756-.36.19 0 .378.002.543.01.174.008.408-.066.638.487.242.578.822 1.997.894 2.142.07.144.117.313.023.503-.093.19-.14.31-.28.478-.14.168-.294.375-.42.504-.14.144-.286.3-.123.588.164.29.73 1.204 1.567 1.95 1.076.96 1.983 1.257 2.273 1.4.29.144.46.121.63-.073.17-.194.727-.85.922-1.142.194-.29.388-.242.654-.145.266.097 1.685.795 1.974.94.29.144.483.216.554.337.07.121.07.7-.172 1.38z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-emerald-800">Tanya PJ Kelas via WhatsApp</p>
                            <p class="text-xs text-emerald-600">{{ $kelas->pj_name ?: 'Penanggung Jawab' }}{{ $kelas->pj_name ? ' · ' : '' }}Chat langsung, respons lebih cepat.</p>
                        </div>
                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                @endif

                @if ($kelas->capacity > 0)
                    @php $pct = min(100, round(($kelas->active_registrations_count / $kelas->capacity) * 100)); @endphp
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-600">{{ $kelas->active_registrations_count }} / {{ $kelas->capacity }} peserta</span>
                            <span class="text-slate-400">{{ $pct }}%</span>
                        </div>
                        <div class="mt-1.5 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="mt-8 border-t border-slate-100 pt-6">
                    @auth
                        @if ($kelas->isRegisteredBy(auth()->user()))
                            <div class="flex items-center gap-2 rounded-xl bg-primary-50 px-4 py-3 text-sm font-medium text-primary-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Kamu sudah terdaftar di kelas ini.
                            </div>
                        @elseif ($kelas->status !== 'dibuka')
                            <div class="rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-medium text-slate-500">Pendaftaran kelas ini belum dibuka.</div>
                        @elseif ($kelas->isFull())
                            <div class="rounded-xl bg-sunny-50 px-4 py-3 text-center text-sm font-medium text-sunny-700">Kuota kelas ini sudah penuh.</div>
                        @elseif ($kelas->registrationForm)
                            <a href="{{ route('form.show', $kelas->registrationForm) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg sm:w-auto">
                                Isi Form Pendaftaran
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </a>
                        @else
                            <form
                                method="POST" action="{{ route('kelas.register', $kelas) }}"
                                x-data="{ submitting: false }" @submit="submitting = true"
                            >
                                @csrf
                                <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:opacity-60 sm:w-auto">
                                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span x-text="submitting ? 'Memproses...' : 'Daftar Kelas Ini'"></span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            Masuk untuk Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
