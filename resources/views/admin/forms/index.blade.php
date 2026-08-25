<x-layouts.admin title="Form Builder">
    <div
        x-data="{ deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Form Builder</h2>
                <p class="mt-1 text-sm text-slate-500">Buat form pendaftaran custom tanpa coding.</p>
            </div>
            <a href="{{ route('admin.forms.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Buat Form
            </a>
        </div>

        @if ($forms->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-primary-100">
                    <svg class="h-7 w-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                </div>
                <p class="mt-4 font-display text-lg font-semibold text-slate-700">Belum ada form</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Buat form pertama untuk pendaftaran kelas, kegiatan, atau volunteer.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($forms as $form)
                    <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-display font-semibold text-slate-800">{{ $form->name }}</h3>
                            @if ($form->target)
                                <span class="shrink-0 rounded-full bg-primary-100 px-2 py-0.5 text-[11px] font-medium text-primary-700">{{ $form->target_type === 'kelas' ? 'Kelas' : 'Kegiatan' }}</span>
                            @else
                                <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-500">Mandiri</span>
                            @endif
                        </div>
                        @if ($form->target)
                            <p class="mt-1 truncate text-xs text-primary-600">&rarr; {{ $form->target->title }}</p>
                        @endif
                        <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $form->description ?: 'Tanpa deskripsi.' }}</p>
                        <p class="mt-3 flex items-center gap-3 text-xs text-slate-400">
                            <span>{{ $form->fields_count }} field</span>
                            <span>&middot;</span>
                            <span>{{ $form->submissions_count }} respons</span>
                        </p>

                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3.5 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <a href="{{ route('admin.forms.submissions', $form) }}" class="inline-flex items-center justify-center rounded-lg bg-slate-100 p-2 text-slate-500 transition hover:bg-primary-100 hover:text-primary-700" title="Respons">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                            </a>
                            <a href="{{ route('form.show', $form) }}" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-slate-100 p-2 text-slate-500 transition hover:bg-primary-100 hover:text-primary-700" title="Lihat form publik">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            </a>
                            <a href="{{ route('admin.forms.edit', $form) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                Edit
                            </a>
                            <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.forms.destroy', $form) }}'; deleteName = '{{ $form->name }}'" class="inline-flex items-center justify-center rounded-lg bg-slate-100 p-2 text-slate-500 transition hover:bg-rose-100 hover:text-rose-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $forms->links() }}</div>
        @endif

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus form?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Form <span class="font-semibold" x-text="deleteName"></span> beserta seluruh field dan respons akan dihapus permanen.</p>
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
