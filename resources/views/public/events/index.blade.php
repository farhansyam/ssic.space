<x-layouts.public title="Kegiatan">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="font-display text-3xl font-bold text-slate-800 sm:text-4xl">Kegiatan Komunitas</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-500">Ikut kegiatan sosial bareng SSIC dan lihat dokumentasi keseruan sebelumnya.</p>
        </div>

        <form method="GET" class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('kegiatan.index') }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('status') ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Semua</a>
            <a href="{{ route('kegiatan.index', ['status' => 'upcoming']) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'upcoming' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Akan Datang</a>
            <a href="{{ route('kegiatan.index', ['status' => 'selesai']) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'selesai' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Selesai</a>
        </form>

        @if ($events->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada kegiatan</p>
                <p class="mt-1 text-sm text-slate-500">Coba lagi nanti atau ubah filter.</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($events as $event)
                    <a href="{{ route('kegiatan.show', $event) }}" class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-40 w-full overflow-hidden bg-gradient-to-br from-cokelat-500 to-primary-700">
                            @if ($event->image)
                                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $event->image_focal_x }}% {{ $event->image_focal_y }}%">
                            @endif
                            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $event->status === 'upcoming' ? 'Akan Datang' : 'Selesai' }}</span>
                        </div>
                        <div class="p-5">
                            <p class="flex items-center gap-1.5 text-xs font-medium text-primary-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                {{ $event->event_date->translatedFormat('d M Y') }}
                            </p>
                            <h3 class="mt-1.5 font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $event->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $event->description }}</p>
                            <p class="mt-3 flex items-center gap-1.5 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $event->location ?: 'Lokasi menyusul' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $events->links() }}</div>
        @endif
    </div>
</x-layouts.public>
