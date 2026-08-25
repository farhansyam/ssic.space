<x-layouts.admin :title="'Dana - '.$campaign->title">
    <div
        x-data="{
            formModal: false,
            deleteModal: false,
            deleteAction: '',
        }"
        @keydown.escape.window="formModal = false; deleteModal = false"
    >
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.donation-campaigns.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div class="flex-1">
                <h2 class="font-display text-2xl font-semibold text-slate-800">Laporan Penyaluran Dana</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $campaign->title }}</p>
            </div>
            <button type="button" @click="formModal = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Catat Penyaluran
            </button>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-5 text-white shadow-playful">
                <p class="text-xs font-medium text-white/80">Dana Terkumpul</p>
                <p class="mt-1 font-display text-2xl font-bold">Rp{{ number_format($campaign->collectedAmount(), 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-sunny-400 to-sunny-500 p-5 text-white shadow-playful">
                <p class="text-xs font-medium text-white/80">Sudah Disalurkan</p>
                <p class="mt-1 font-display text-2xl font-bold">Rp{{ number_format($campaign->disbursedAmount(), 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-700 to-primary-900 p-5 text-white shadow-playful">
                <p class="text-xs font-medium text-white/80">Sisa Dana</p>
                <p class="mt-1 font-display text-2xl font-bold">Rp{{ number_format($campaign->remainingAmount(), 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between text-xs">
                <span class="font-medium text-slate-600">Progres Penyaluran</span>
                <span class="text-slate-400">{{ $campaign->disbursedPercent() }}%</span>
            </div>
            <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-gradient-to-r from-sunny-400 to-sunny-500 transition-all duration-500" style="width: {{ $campaign->disbursedPercent() }}%"></div>
            </div>
        </div>

        @if ($campaign->disbursements->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada catatan penyaluran</p>
                <p class="mt-1 text-sm text-slate-500">Catat penyaluran dana pertama lewat tombol di atas.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($campaign->disbursements as $item)
                    <div class="group flex flex-col gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-playful sm:flex-row sm:items-center">
                        @if ($item->proof_image)
                            <img src="{{ Storage::url($item->proof_image) }}" alt="Bukti penyaluran" class="h-20 w-20 flex-shrink-0 rounded-xl object-cover">
                        @else
                            <div class="grid h-20 w-20 flex-shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-300">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 22.5H6a2.25 2.25 0 01-2.25-2.25V3.75A2.25 2.25 0 016 1.5h12a2.25 2.25 0 012.25 2.25v16.5A2.25 2.25 0 0118 22.5z" /></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-display font-semibold text-slate-800">Rp{{ number_format($item->amount, 0, ',', '.') }}</p>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $item->description }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $item->disbursed_at->translatedFormat('d M Y') }}</p>
                        </div>
                        <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.donation-campaigns.disbursements.destroy', [$campaign, $item]) }}'" class="inline-flex items-center justify-center gap-1.5 self-start rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 opacity-0 transition-all duration-200 hover:bg-rose-100 hover:text-rose-700 group-hover:opacity-100 sm:self-center">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            Hapus
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Create modal -->
        <div x-show="formModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="formModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="formModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="formModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800">Catat Penyaluran Dana</p>
                <form method="POST" action="{{ route('admin.donation-campaigns.disbursements.store', $campaign) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea name="description" required rows="2" placeholder="Contoh: Pembelian 50 paket sembako" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah (Rp)</label>
                            <input type="number" name="amount" required min="0" step="1000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal</label>
                            <input type="date" name="disbursed_at" required value="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Bukti Penyaluran (opsional)</label>
                        <input type="file" name="proof_image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="formModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus catatan ini?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Catatan penyaluran dana akan dihapus permanen.</p>
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
