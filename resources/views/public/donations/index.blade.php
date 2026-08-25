<x-layouts.public title="Donasi">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="font-display text-3xl font-bold text-slate-800 sm:text-4xl">Donasi &amp; Fundraising</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-500">Salurkan bantuan lewat campaign donasi yang transparan bareng SSIC.</p>
            <a href="{{ route('donasi.umum') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-primary-200 bg-primary-50 px-5 py-2.5 text-sm font-semibold text-primary-700 transition hover:-translate-y-0.5 hover:shadow-playful">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                Donasi Umum (tanpa campaign)
            </a>
        </div>

        @if ($campaigns->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada campaign donasi</p>
                <p class="mt-1 text-sm text-slate-500">Kamu tetap bisa donasi lewat "Donasi Umum" di atas.</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <a href="{{ route('donasi.show', $campaign) }}" class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-40 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($campaign->image)
                                <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $campaign->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $campaign->description }}</p>

                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-primary-600">Rp{{ number_format($campaign->collectedAmount(), 0, ',', '.') }}</span>
                                    <span class="text-slate-400">dari Rp{{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-1.5 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700" style="width: {{ $campaign->progressPercent() }}%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $campaigns->links() }}</div>
        @endif
    </div>
</x-layouts.public>
