<x-layouts.public title="Kelas">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="font-display text-3xl font-bold text-slate-800 sm:text-4xl">Kelas &amp; Program Belajar</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-500">Ikut kelas gratis maupun berbayar, belajar bareng komunitas SSIC.</p>
        </div>

        <form method="GET" class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <select name="kategori" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                <option value="">Semua Kategori</option>
                <option value="gratis" @selected(request('kategori') === 'gratis')>Gratis</option>
                <option value="berbayar" @selected(request('kategori') === 'berbayar')>Berbayar</option>
            </select>
            <select name="level" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                <option value="">Semua Level</option>
                <option value="beginner" @selected(request('level') === 'beginner')>Beginner</option>
                <option value="intermediate" @selected(request('level') === 'intermediate')>Intermediate</option>
                <option value="advanced" @selected(request('level') === 'advanced')>Advanced</option>
            </select>
        </form>

        @if ($classes->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada kelas yang tersedia</p>
                <p class="mt-1 text-sm text-slate-500">Coba lagi nanti atau ubah filter.</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($classes as $kelas)
                    <a href="{{ route('kelas.show', $kelas) }}" class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-40 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($kelas->image)
                                <img src="{{ Storage::url($kelas->image) }}" alt="{{ $kelas->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $kelas->image_focal_x }}% {{ $kelas->image_focal_y }}%">
                            @endif
                            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold capitalize text-slate-700">{{ $kelas->category }}</span>
                            @if ($kelas->status === 'penuh')
                                <span class="absolute left-3 top-3 rounded-full bg-sunny-400 px-2.5 py-1 text-xs font-semibold text-white">Penuh</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium capitalize text-slate-500">{{ $kelas->level }}</span>
                            <h3 class="mt-2.5 font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $kelas->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $kelas->description }}</p>
                            <p class="mt-3 flex items-center gap-1.5 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $kelas->schedule ?: 'Jadwal menyusul' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $classes->links() }}</div>
        @endif
    </div>
</x-layouts.public>
