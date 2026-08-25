<x-layouts.admin title="Tag Blog">
    <div
        x-data="{ editModal: false, editAction: '', editName: '', deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="editModal = false; deleteModal = false"
    >
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Tag Blog</h2>
                <p class="mt-0.5 text-sm text-slate-500">Kelola tag artikel blog.</p>
            </div>
        </div>

        <div
            x-data="{ name: '', submitting: false }"
            class="mb-6 max-w-md rounded-2xl border border-slate-100 bg-white p-5 shadow-sm"
        >
            <form method="POST" action="{{ route('admin.post-tags.store') }}" @submit="submitting = true" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Tag Baru</label>
                    <input id="name" name="name" type="text" x-model="name" required maxlength="100" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: volunteer">
                </div>
                <button type="submit" :disabled="submitting || !name" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    Tambah
                </button>
            </form>
            @error('name') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @if ($tags->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada tag</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan tag pertama lewat form di atas.</p>
            </div>
        @else
            <div class="flex flex-wrap gap-2.5">
                @foreach ($tags as $tag)
                    <div class="group flex items-center gap-2 rounded-full border border-slate-200 bg-white py-2 pl-4 pr-2 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-primary-200 hover:shadow-playful">
                        <span class="text-sm font-medium text-slate-600">#{{ $tag->name }}</span>
                        <span class="rounded-full bg-slate-100 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">{{ $tag->posts_count }}</span>
                        <button type="button" @click="editModal = true; editAction = '{{ route('admin.post-tags.update', $tag) }}'; editName = '{{ $tag->name }}'" class="grid h-6 w-6 place-items-center rounded-full text-slate-400 transition hover:bg-primary-50 hover:text-primary-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                        </button>
                        <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.post-tags.destroy', $tag) }}'; deleteName = '{{ $tag->name }}'" class="grid h-6 w-6 place-items-center rounded-full text-slate-400 transition hover:bg-rose-100 hover:text-rose-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $tags->links() }}</div>
        @endif

        <!-- Edit modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="editModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="editModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="editModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800">Edit Tag</p>
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
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus tag?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Tag <span class="font-semibold" x-text="deleteName"></span> akan dihapus dari semua artikel.</p>
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
