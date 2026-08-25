<x-layouts.public
    :title="$campaign->seoMeta?->meta_title ?? $campaign->title"
    :description="$campaign->seoMeta?->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($campaign->description), 160)"
    :image="$campaign->seoMeta?->og_image ?? $campaign->image"
>
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('donasi.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke daftar campaign
        </a>

        <div class="mt-4 grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                    <div class="relative h-56 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600 sm:h-72">
                        @if ($campaign->image)
                            <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="p-6 sm:p-8">
                        <h1 class="font-display text-2xl font-bold text-slate-800 sm:text-3xl">{{ $campaign->title }}</h1>

                        <div class="mt-5">
                            <div class="flex items-baseline justify-between">
                                <span class="font-display text-2xl font-bold text-primary-600">Rp{{ number_format($campaign->collectedAmount(), 0, ',', '.') }}</span>
                                <span class="text-sm text-slate-400">dari Rp{{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700" style="width: {{ $campaign->progressPercent() }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $campaign->progressPercent() }}% tercapai</span>
                                @if ($campaign->deadline)
                                    <span>Tenggat {{ $campaign->deadline->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <p class="mt-6 whitespace-pre-line text-slate-600">{{ $campaign->description }}</p>
                    </div>
                </div>

                @if ($campaign->disbursements->isNotEmpty())
                    <div class="mt-8 overflow-hidden rounded-3xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-display text-lg font-semibold text-slate-800">Laporan Penyaluran Dana</h2>
                        <p class="mt-1 text-sm text-slate-500">Transparansi penggunaan dana yang sudah terkumpul.</p>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-sunny-50 p-4">
                                <p class="text-xs font-medium text-sunny-700">Sudah Disalurkan</p>
                                <p class="mt-1 font-display text-xl font-bold text-sunny-700">Rp{{ number_format($campaign->disbursedAmount(), 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl bg-primary-50 p-4">
                                <p class="text-xs font-medium text-primary-700">Sisa Dana</p>
                                <p class="mt-1 font-display text-xl font-bold text-primary-700">Rp{{ number_format($campaign->remainingAmount(), 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-5 border-l-2 border-dashed border-slate-200 pl-5">
                            @foreach ($campaign->disbursements as $item)
                                <div class="relative">
                                    <span class="absolute -left-[27px] top-1 grid h-4 w-4 place-items-center rounded-full bg-sunny-400 ring-4 ring-white"></span>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                        @if ($item->proof_image)
                                            <img src="{{ Storage::url($item->proof_image) }}" alt="Bukti penyaluran" loading="lazy" class="h-16 w-16 flex-shrink-0 rounded-xl object-cover">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800">Rp{{ number_format($item->amount, 0, ',', '.') }}</p>
                                            <p class="mt-0.5 text-sm text-slate-500">{{ $item->description }}</p>
                                            <p class="mt-1 text-xs text-slate-400">{{ $item->disbursed_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2">
                @include('public.donations._donate-form', ['action' => route('donasi.store', $campaign)])
            </div>
        </div>
    </div>
</x-layouts.public>
