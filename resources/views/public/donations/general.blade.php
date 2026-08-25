<x-layouts.public title="Donasi Umum">
    <div class="mx-auto max-w-lg px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('donasi.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke daftar campaign
        </a>

        <div class="mt-4 text-center">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-sunny-100 text-sunny-600">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            </div>
            <h1 class="mt-4 font-display text-2xl font-bold text-slate-800">Donasi Umum</h1>
            <p class="mt-1 text-sm text-slate-500">Donasi tanpa terikat campaign tertentu — akan dikelola langsung oleh SSIC.</p>
        </div>

        <div class="mt-6">
            @include('public.donations._donate-form', ['action' => route('donasi.umum.store')])
        </div>
    </div>
</x-layouts.public>
