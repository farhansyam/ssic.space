<x-layouts.public
    title="Open Recruitment"
    description="Gabung jadi volunteer SSIC. Cek lowongan open recruitment yang sedang dibuka."
>
    <div class="relative overflow-hidden bg-gradient-to-br from-cokelat-700 to-primary-800 px-4 py-16 text-center sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 overflow-hidden opacity-20">
            <div class="absolute -left-10 top-6 h-40 w-40 rounded-full bg-white blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-white blur-3xl"></div>
        </div>
        <div class="relative">
            <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">Open Recruitment</h1>
            <p class="mx-auto mt-3 max-w-md text-primary-100">Gabung jadi bagian dari SSIC dan bantu bikin dampak sosial yang nyata.</p>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($forms->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada lowongan dibuka</p>
                <p class="mt-1 text-sm text-slate-500">Pantau terus halaman ini, lowongan volunteer baru akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($forms as $form)
                    <a href="{{ route('form.show', $form) }}" class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="grid h-12 w-12 flex-shrink-0 place-items-center rounded-2xl bg-cokelat-100 text-cokelat-700 transition-colors group-hover:bg-cokelat-600 group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-display font-semibold text-slate-800">{{ $form->name }}</h3>
                            <p class="mt-0.5 truncate text-sm text-slate-500">{{ $form->description }}</p>
                        </div>
                        <svg class="h-5 w-5 flex-shrink-0 text-slate-300 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-cokelat-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.public>
