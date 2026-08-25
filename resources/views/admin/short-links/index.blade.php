<x-layouts.admin title="Short Link">
    <div
        x-data="{
            editModal: false, editAction: '', editSlug: '', editUrl: '',
            deleteModal: false, deleteAction: '', deleteSlug: '',
            copyFeedback: null,
            copy(text, id) {
                navigator.clipboard.writeText(text);
                this.copyFeedback = id;
                setTimeout(() => this.copyFeedback = null, 1500);
            },
        }"
        @keydown.escape.window="editModal = false; deleteModal = false"
    >
        <div class="mb-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800">Short Link</h2>
            <p class="mt-1 text-sm text-slate-500">Buat link pendek dari domain sendiri, dengan tracking klik.</p>
        </div>

        <div
            x-data="{ slug: '', targetUrl: '', submitting: false }"
            class="mb-6 max-w-2xl rounded-2xl border border-slate-100 bg-white p-5 shadow-sm"
        >
            <form method="POST" action="{{ route('admin.short-links.store') }}" @submit="submitting = true" class="grid gap-3 sm:grid-cols-[1fr_1.5fr_auto] sm:items-end">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Slug <span class="text-slate-400">(opsional)</span></label>
                    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 pl-3 text-sm focus-within:border-primary-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-primary-200">
                        <span class="text-slate-400">/</span>
                        <input type="text" name="slug" x-model="slug" maxlength="50" class="w-full bg-transparent px-1 py-2.5 outline-none" placeholder="auto">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">URL Tujuan</label>
                    <input type="url" name="target_url" x-model="targetUrl" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="https://...">
                </div>
                <button type="submit" :disabled="submitting || !targetUrl" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    Buat
                </button>
            </form>
            @error('slug') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
            @error('target_url') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @if ($links->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada short link</p>
                <p class="mt-1 text-sm text-slate-500">Buat short link pertama lewat form di atas.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-5 py-3 font-medium">Short Link</th>
                            <th class="px-5 py-3 font-medium">Tujuan</th>
                            <th class="px-5 py-3 font-medium">Klik</th>
                            <th class="px-5 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($links as $link)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-medium text-primary-600">/{{ $link->slug }}</span>
                                        <button type="button" @click="copy('{{ url('/'.$link->slug) }}', {{ $link->id }})" class="text-slate-300 transition hover:text-primary-600" title="Salin link">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                                        </button>
                                        <span x-show="copyFeedback === {{ $link->id }}" x-cloak x-transition class="text-xs font-medium text-emerald-600">Disalin!</span>
                                    </div>
                                </td>
                                <td class="max-w-xs truncate px-5 py-3.5 text-slate-500">{{ $link->target_url }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700">{{ $link->click_count }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" @click="editModal = true; editAction = '{{ route('admin.short-links.update', $link) }}'; editSlug = '{{ $link->slug }}'; editUrl = '{{ $link->target_url }}'" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                        </button>
                                        <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.short-links.destroy', $link) }}'; deleteSlug = '{{ $link->slug }}'" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 transition hover:bg-rose-100 hover:text-rose-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $links->links() }}</div>
        @endif

        <!-- Edit modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="editModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="editModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="editModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800">Edit Short Link</p>
                <form :action="editAction" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Slug</label>
                        <input type="text" name="slug" x-model="editSlug" required maxlength="50" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">URL Tujuan</label>
                        <input type="url" name="target_url" x-model="editUrl" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div class="flex gap-3">
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
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus short link?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Link <span class="font-semibold" x-text="'/' + deleteSlug"></span> akan dihapus permanen.</p>
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
