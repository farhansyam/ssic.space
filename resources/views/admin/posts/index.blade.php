<x-layouts.admin title="Blog">
    <div
        x-data="{ deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Blog</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola artikel, kategori, dan tag blog.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.post-categories.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">Kategori</a>
                <a href="{{ route('admin.post-tags.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">Tag</a>
                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tulis Artikel
                </a>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-3">
            <form method="GET" class="flex-1">
                <div class="relative max-w-sm">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                </div>
            </form>
            <div class="flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="rounded-xl px-3.5 py-2 text-sm font-medium transition {{ !request('status') ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200' }}">Semua</a>
                <a href="{{ route('admin.posts.index', ['status' => 'publish']) }}" class="rounded-xl px-3.5 py-2 text-sm font-medium transition {{ request('status') === 'publish' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200' }}">Publish</a>
                <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="rounded-xl px-3.5 py-2 text-sm font-medium transition {{ request('status') === 'draft' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200' }}">Draft</a>
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-primary-100">
                    <svg class="h-7 w-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <p class="mt-4 font-display text-lg font-semibold text-slate-700">Belum ada artikel</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Mulai tulis artikel pertama untuk blog SSIC.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($posts as $post)
                    <div class="flex flex-col gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-playful sm:flex-row sm:items-center">
                        <div class="h-16 w-24 shrink-0 overflow-hidden rounded-xl bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-display font-semibold text-slate-800">{{ $post->title }}</p>
                                <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold capitalize {{ $post->status === 'publish' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $post->status }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">{{ $post->category->name ?? 'Tanpa kategori' }} &middot; {{ $post->author->name }} &middot; {{ $post->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3.5 py-2 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                Edit
                            </a>
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.posts.destroy', $post) }}'; deleteName = '{{ $post->title }}'" class="inline-flex items-center justify-center rounded-lg bg-slate-100 p-2 text-slate-500 transition hover:bg-rose-100 hover:text-rose-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
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
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-rose-100">
                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <p class="mt-4 text-center font-display text-lg font-semibold text-slate-800">Hapus artikel?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Artikel <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen.</p>
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
