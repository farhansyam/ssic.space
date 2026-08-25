<x-layouts.admin title="Galeri Instagram">
    <div
        x-data="{ deleteModal: false, deleteAction: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800">Galeri Instagram</h2>
            <p class="mt-1 text-sm text-slate-500">Upload manual postingan terbaru sebagai fallback feed &commat;ssic.impact (tanpa perlu setup Meta App/Graph API).</p>
        </div>

        <div
            x-data="{ submitting: false, preview: null, onFile(e) { const f = e.target.files[0]; this.preview = f ? URL.createObjectURL(f) : null; } }"
            class="mb-6 max-w-2xl rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
        >
            <form method="POST" action="{{ route('admin.instagram-posts.store') }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-4">
                @csrf
                <div class="flex items-center gap-4">
                    <div class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-xl bg-slate-100">
                        <template x-if="preview"><img :src="preview" class="h-full w-full object-cover"></template>
                        <template x-if="!preview"><svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 3.75h-12v16.5h12V3.75z" /></svg></template>
                    </div>
                    <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                        <span>Klik untuk pilih foto (JPG/PNG, maks 2MB)</span>
                        <input type="file" name="media_url" accept="image/png,image/jpeg" required @change="onFile" class="hidden">
                    </label>
                </div>
                @error('media_url') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Caption <span class="text-slate-400">(opsional)</span></label>
                        <textarea name="caption" rows="2" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Link Instagram <span class="text-slate-400">(opsional)</span></label>
                        <input type="url" name="permalink" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="https://instagram.com/p/...">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Posting</label>
                        <input type="date" name="posted_at" value="{{ now()->format('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                </div>

                <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60">
                    <span x-text="submitting ? 'Mengunggah...' : 'Tambah ke Galeri'"></span>
                </button>
            </form>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Galeri masih kosong</p>
                <p class="mt-1 text-sm text-slate-500">Upload postingan pertama lewat form di atas.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($posts as $post)
                    <div class="group relative aspect-square overflow-hidden rounded-2xl border border-slate-100 bg-slate-100 shadow-sm">
                        <img src="{{ Storage::url($post->media_url) }}" alt="{{ $post->caption }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-slate-900/70 via-transparent to-transparent p-3 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            @if ($post->caption)
                                <p class="truncate text-xs text-white">{{ $post->caption }}</p>
                            @endif
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.instagram-posts.destroy', $post) }}'" class="mt-1.5 inline-flex w-fit items-center gap-1 rounded-lg bg-white/90 px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-white">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $posts->links() }}</div>
        @endif

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
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
