<x-layouts.admin title="Galeri Kegiatan">
    <div x-data="{ deleteModal: false, deleteAction: '' }" @keydown.escape.window="deleteModal = false">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Galeri: {{ $event->title }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $event->galleries->count() }} foto dokumentasi</p>
            </div>
        </div>

        <div
            x-data="{
                submitting: false,
                files: [],
                onFiles(e) { this.files = Array.from(e.target.files).map(f => URL.createObjectURL(f)); },
            }"
            class="mb-8 max-w-2xl rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
        >
            <form method="POST" action="{{ route('admin.events.gallery.store', $event) }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Pilih Foto <span class="text-slate-400">(bisa lebih dari satu, maks 2MB/foto)</span></label>
                    <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Klik untuk pilih foto galeri</span>
                        <input type="file" name="images[]" accept="image/png,image/jpeg" multiple @change="onFiles" class="hidden">
                    </label>
                    @error('images') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div x-show="files.length > 0" x-cloak class="grid grid-cols-4 gap-2 sm:grid-cols-6">
                    <template x-for="(src, i) in files" :key="i">
                        <img :src="src" class="h-16 w-16 rounded-lg object-cover">
                    </template>
                </div>

                <div>
                    <label for="caption" class="mb-1.5 block text-sm font-medium text-slate-700">Caption <span class="text-slate-400">(opsional, berlaku untuk semua foto)</span></label>
                    <input id="caption" name="caption" type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Sesi pembukaan acara">
                </div>

                <button type="submit" :disabled="submitting || files.length === 0" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span x-text="submitting ? 'Mengunggah...' : 'Unggah Foto'"></span>
                </button>
            </form>
        </div>

        @if ($event->galleries->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada foto galeri</p>
                <p class="mt-1 text-sm text-slate-500">Unggah dokumentasi kegiatan lewat form di atas.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($event->galleries as $photo)
                    <div class="group relative aspect-square overflow-hidden rounded-2xl border border-slate-100 bg-slate-100 shadow-sm">
                        <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->caption }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-slate-900/70 via-transparent to-transparent p-3 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            @if ($photo->caption)
                                <p class="truncate text-xs text-white">{{ $photo->caption }}</p>
                            @endif
                            <button
                                type="button"
                                @click="deleteModal = true; deleteAction = '{{ route('admin.events.gallery.destroy', [$event, $photo]) }}'"
                                class="mt-1.5 inline-flex w-fit items-center gap-1 rounded-lg bg-white/90 px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-white"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus foto ini?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Foto akan dihapus permanen dari galeri.</p>
                <div class="mt-6 flex gap-3">
                    <button @click="deleteModal = false" type="button" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
