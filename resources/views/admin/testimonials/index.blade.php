<x-layouts.admin title="Testimoni">
    <div
        x-data="{
            formModal: false,
            mode: 'create',
            action: '{{ route('admin.testimonials.store') }}',
            name: '',
            role: '',
            content: '',
            rating: 5,
            photoPreview: null,
            deleteModal: false,
            deleteAction: '',
            deleteName: '',
            openCreate() {
                this.mode = 'create';
                this.action = '{{ route('admin.testimonials.store') }}';
                this.name = ''; this.role = ''; this.content = ''; this.rating = 5; this.photoPreview = null;
                this.formModal = true;
            },
            openEdit(t) {
                this.mode = 'edit';
                this.action = t.action;
                this.name = t.name; this.role = t.role; this.content = t.content; this.rating = t.rating;
                this.photoPreview = t.photo;
                this.formModal = true;
            },
            onPhoto(e) {
                const file = e.target.files[0];
                if (file) this.photoPreview = URL.createObjectURL(file);
            },
        }"
        @keydown.escape.window="formModal = false; deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Testimoni</h2>
                <p class="mt-1 text-sm text-slate-500">Testimoni anggota/mitra yang tampil di halaman utama.</p>
            </div>
            <button type="button" @click="openCreate()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Testimoni
            </button>
        </div>

        @if ($testimonials->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada testimoni</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan testimoni pertama lewat tombol di atas.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $t)
                    <div class="group flex flex-col rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="flex items-center gap-3">
                            <div class="grid h-12 w-12 flex-shrink-0 place-items-center overflow-hidden rounded-full bg-gradient-to-br from-primary-400 to-primary-600 font-display font-semibold text-white">
                                @if ($t->photo)
                                    <img src="{{ Storage::url($t->photo) }}" alt="{{ $t->name }}" class="h-full w-full object-cover">
                                @else
                                    {{ strtoupper(substr($t->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-display font-semibold text-slate-800">{{ $t->name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ $t->role_or_status }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $t->rating ? 'text-sunny-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                            @endfor
                        </div>
                        <p class="mt-2.5 flex-1 text-sm text-slate-600">&ldquo;{{ \Illuminate\Support\Str::limit($t->content, 120) }}&rdquo;</p>
                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3.5 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <button type="button" @click="openEdit({
                                    action: '{{ route('admin.testimonials.update', $t) }}',
                                    name: {{ Illuminate\Support\Js::from($t->name) }},
                                    role: {{ Illuminate\Support\Js::from($t->role_or_status) }},
                                    content: {{ Illuminate\Support\Js::from($t->content) }},
                                    rating: {{ $t->rating }},
                                    photo: {{ Illuminate\Support\Js::from($t->photo ? Storage::url($t->photo) : null) }},
                                })" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                Edit
                            </button>
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.testimonials.destroy', $t) }}'; deleteName = '{{ $t->name }}'" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-rose-100 hover:text-rose-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $testimonials->links() }}</div>
        @endif

        <!-- Create/Edit modal -->
        <div x-show="formModal" x-cloak class="fixed inset-0 z-50 grid place-items-center overflow-y-auto px-4 py-8">
            <div x-show="formModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="formModal = false" class="fixed inset-0 bg-slate-900/50"></div>
            <div x-show="formModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800" x-text="mode === 'create' ? 'Tambah Testimoni' : 'Edit Testimoni'"></p>
                <form :action="action" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                    <div class="flex items-center gap-4">
                        <div class="grid h-16 w-16 flex-shrink-0 place-items-center overflow-hidden rounded-full bg-slate-100">
                            <img x-show="photoPreview" :src="photoPreview" class="h-full w-full object-cover">
                            <svg x-show="!photoPreview" class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <div class="flex-1">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Foto (opsional)</label>
                            <input type="file" name="photo" accept="image/*" @change="onPhoto($event)" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
                        <input type="text" name="name" x-model="name" required maxlength="150" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Peran / Status</label>
                        <input type="text" name="role_or_status" x-model="role" maxlength="150" placeholder="Contoh: Anggota Divisi Pendidikan" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Rating</label>
                        <div class="flex gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="rating = i" class="transition hover:scale-110">
                                    <svg class="h-6 w-6" :class="i <= rating ? 'text-sunny-400' : 'text-slate-200'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.285-3.958z"/></svg>
                                </button>
                            </template>
                            <input type="hidden" name="rating" x-model="rating">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Isi Testimoni</label>
                        <textarea name="content" x-model="content" required rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="formModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus testimoni?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Testimoni dari <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen.</p>
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
