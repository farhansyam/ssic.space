<x-layouts.admin title="Badge">
    <div
        x-data="{
            formModal: false,
            mode: 'create',
            action: '{{ route('admin.badges.store') }}',
            name: '', description: '', icon: '🏅', threshold: 100,
            openCreate() {
                this.mode = 'create';
                this.action = '{{ route('admin.badges.store') }}';
                this.name = ''; this.description = ''; this.icon = '🏅'; this.threshold = 100;
                this.formModal = true;
            },
            openEdit(b) {
                this.mode = 'edit';
                this.action = b.action;
                this.name = b.name; this.description = b.description; this.icon = b.icon; this.threshold = b.threshold;
                this.formModal = true;
            },
            deleteModal: false, deleteAction: '', deleteName: '',
        }"
        @keydown.escape.window="formModal = false; deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Badge Volunteer</h2>
                <p class="mt-1 text-sm text-slate-500">Badge otomatis didapat saat member mencapai jumlah poin tertentu.</p>
            </div>
            <button type="button" @click="openCreate()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Badge
            </button>
        </div>

        @if ($badges->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada badge</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan badge pertama lewat tombol di atas.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($badges as $badge)
                    <div class="group flex flex-col rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="flex items-center gap-3">
                            <div class="grid h-12 w-12 flex-shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-sunny-300 to-sunny-500 text-2xl shadow-playful">{{ $badge->icon }}</div>
                            <div class="min-w-0">
                                <p class="truncate font-display font-semibold text-slate-800">{{ $badge->name }}</p>
                                <p class="text-xs text-slate-400">Min. {{ $badge->criteria_json['value'] ?? 0 }} poin</p>
                            </div>
                        </div>
                        <p class="mt-2.5 flex-1 text-sm text-slate-500">{{ $badge->description }}</p>
                        <p class="mt-2 text-xs text-slate-400">{{ $badge->user_badges_count }} member meraih badge ini</p>
                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3.5 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <button type="button" @click="openEdit({
                                    action: '{{ route('admin.badges.update', $badge) }}',
                                    name: {{ Illuminate\Support\Js::from($badge->name) }},
                                    description: {{ Illuminate\Support\Js::from($badge->description) }},
                                    icon: {{ Illuminate\Support\Js::from($badge->icon) }},
                                    threshold: {{ $badge->criteria_json['value'] ?? 0 }},
                                })" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                Edit
                            </button>
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.badges.destroy', $badge) }}'; deleteName = '{{ $badge->name }}'" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-rose-100 hover:text-rose-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Create/Edit modal -->
        <div x-show="formModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="formModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="formModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="formModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800" x-text="mode === 'create' ? 'Tambah Badge' : 'Edit Badge'"></p>
                <form :action="action" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                    <div class="flex gap-3">
                        <div class="w-20">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Ikon</label>
                            <input type="text" name="icon" x-model="icon" maxlength="10" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-center text-xl outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                        <div class="flex-1">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Badge</label>
                            <input type="text" name="name" x-model="name" required maxlength="150" placeholder="Contoh: Volunteer Emas" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea name="description" x-model="description" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Minimal Poin</label>
                        <input type="number" name="points_threshold" x-model="threshold" required min="1" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        <p class="mt-1.5 text-xs text-slate-400">Badge otomatis diberikan saat total poin member &ge; angka ini.</p>
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
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus badge?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Badge <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen.</p>
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
