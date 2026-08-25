<x-layouts.admin title="Hero Banner">
    <div
        x-data="{
            banners: {{ Illuminate\Support\Js::from($banners) }},
            dragIndex: null,
            overIndex: null,
            deleteModal: false,
            deleteAction: '',
            deleteTitle: '',
            csrf() { return document.querySelector('meta[name=csrf-token]').content; },
            onDrop(index) {
                if (this.dragIndex === null || this.dragIndex === index) { this.overIndex = null; return; }
                const item = this.banners.splice(this.dragIndex, 1)[0];
                this.banners.splice(index, 0, item);
                this.dragIndex = null;
                this.overIndex = null;
                this.persistOrder();
            },
            async persistOrder() {
                await fetch(`{{ route('admin.hero-banners.reorder') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ order: this.banners.map(b => b.id) }),
                });
            },
        }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Hero Banner</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola slide carousel di halaman depan. Seret untuk mengurutkan.</p>
            </div>
            <a href="{{ route('admin.hero-banners.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Slide
            </a>
        </div>

        <p x-show="banners.length === 0" class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada banner. Klik "Tambah Slide" untuk mulai.</p>

        <div class="space-y-3" x-show="banners.length > 0">
            <template x-for="(banner, index) in banners" :key="banner.id">
                <div
                    draggable="true"
                    @dragstart="dragIndex = index"
                    @dragover.prevent="overIndex = index"
                    @dragleave="overIndex = overIndex === index ? null : overIndex"
                    @drop.prevent="onDrop(index)"
                    @dragend="dragIndex = null; overIndex = null"
                    class="flex cursor-grab items-center gap-4 rounded-2xl border bg-white p-4 shadow-sm transition active:cursor-grabbing"
                    :class="overIndex === index ? 'border-primary-400 bg-primary-50' : 'border-slate-100'"
                >
                    <svg class="h-5 w-5 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                    <img :src="'/storage/' + banner.image" class="h-16 w-28 shrink-0 rounded-xl object-cover">
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-slate-700" x-text="banner.title || '(tanpa judul)'"></p>
                        <p class="truncate text-xs text-slate-400" x-text="banner.subtitle || '—'"></p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium" :class="banner.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'" x-text="banner.is_active ? 'Aktif' : 'Nonaktif'"></span>
                    <div class="flex shrink-0 items-center gap-1.5">
                        <a :href="`/admin/hero-banners/${banner.id}/edit`" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                        </a>
                        <button type="button" @click="deleteModal = true; deleteAction = `/admin/hero-banners/${banner.id}`; deleteTitle = banner.title || 'slide ini'" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-rose-100 hover:text-rose-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus banner ini?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500" x-text="'Slide &quot;' + deleteTitle + '&quot; akan dihapus permanen.'"></p>
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
