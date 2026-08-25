<x-layouts.admin title="Kategori Blog">
    <div
        x-data="{ editModal: false, editAction: '', editName: '', deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="editModal = false; deleteModal = false"
    >
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Kategori Blog</h2>
                <p class="mt-0.5 text-sm text-slate-500">Kelola kategori artikel blog.</p>
            </div>
        </div>

        <div
            x-data="{ name: '', submitting: false }"
            class="mb-6 max-w-md rounded-2xl border border-slate-100 bg-white p-5 shadow-sm"
        >
            <form method="POST" action="{{ route('admin.post-categories.store') }}" @submit="submitting = true" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Kategori Baru</label>
                    <input id="name" name="name" type="text" x-model="name" required maxlength="100" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Kegiatan Sosial">
                </div>
                <button type="submit" :disabled="submitting || !name" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    Tambah
                </button>
            </form>
            @error('name') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @if ($categories->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada kategori</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan kategori pertama lewat form di atas.</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)
                    <div class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-playful">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-700">{{ $category->name }}</p>
                            <p class="text-xs text-slate-400">{{ $category->posts_count }} artikel</p>
                        </div>
                        <div class="flex items-center gap-1.5 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <button type="button" @click="editModal = true; editAction = '{{ route('admin.post-categories.update', $category) }}'; editName = '{{ $category->name }}'" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                            </button>
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.post-categories.destroy', $category) }}'; deleteName = '{{ $category->name }}'" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-rose-100 hover:text-rose-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $categories->links() }}</div>
        @endif

        <!-- Edit modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="editModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="editModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="editModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800">Edit Kategori</p>
                <form :action="editAction" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" x-model="editName" required maxlength="100" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    <div class="mt-4 flex gap-3">
                        <button type="button" @click="editModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus kategori?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500"><span class="font-semibold" x-text="deleteName"></span> akan dihapus. Artikel di kategori ini tidak akan ikut terhapus.</p>
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
