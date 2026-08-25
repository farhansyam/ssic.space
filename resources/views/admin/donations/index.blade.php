<x-layouts.admin title="Konfirmasi Donasi">
    <div x-data="{ proofModal: false, proofImage: '' }" @keydown.escape.window="proofModal = false">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.donation-campaigns.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Konfirmasi Donasi</h2>
                <p class="mt-0.5 text-sm text-slate-500">Verifikasi bukti transfer &amp; konfirmasi donasi masuk.</p>
            </div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-sunny-400 to-sunny-500 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Menunggu Verifikasi</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['pending'] }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Terkonfirmasi</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['terkonfirmasi'] }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Ditolak</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['ditolak'] }}</p>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('admin.donations.index') }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('status') ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Semua</a>
            <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Pending</a>
            <a href="{{ route('admin.donations.index', ['status' => 'terkonfirmasi']) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'terkonfirmasi' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Terkonfirmasi</a>
            <a href="{{ route('admin.donations.index', ['status' => 'ditolak']) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('status') === 'ditolak' ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Ditolak</a>
        </div>

        @if ($donations->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada donasi</p>
                <p class="mt-1 text-sm text-slate-500">Donasi yang masuk akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($donations as $donation)
                    <div class="flex flex-col gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:flex-row sm:items-center">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-display font-semibold text-slate-800">{{ $donation->donor_name }}</p>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {{ $donation->status === 'terkonfirmasi' ? 'bg-emerald-100 text-emerald-700' : ($donation->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-sunny-100 text-sunny-700') }}">{{ $donation->status }}</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">{{ $donation->campaign->title ?? 'Donasi Umum' }} &middot; {{ ucfirst($donation->payment_method) }} &middot; {{ $donation->created_at->translatedFormat('d M Y, H:i') }}</p>
                            @if ($donation->message)
                                <p class="mt-1.5 rounded-lg bg-slate-50 px-3 py-2 text-sm italic text-slate-500">&ldquo;{{ $donation->message }}&rdquo;</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                            <p class="font-display text-lg font-bold text-primary-600">Rp{{ number_format($donation->amount, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-2">
                                @if ($donation->proof_image)
                                    <button type="button" @click="proofModal = true; proofImage = '{{ Storage::url($donation->proof_image) }}'" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">Lihat Bukti</button>
                                @endif
                                @if ($donation->status === 'pending')
                                    <form method="POST" action="{{ route('admin.donations.reject', $donation) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-200">Tolak</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.donations.confirm', $donation) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-600">Konfirmasi</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $donations->links() }}</div>
        @endif

        <div
            x-show="proofModal" x-cloak
            x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            @click="proofModal = false"
            class="fixed inset-0 z-50 grid place-items-center bg-slate-900/80 p-6"
        >
            <img :src="proofImage" class="max-h-[85vh] max-w-full rounded-2xl object-contain shadow-playful-lg" @click.stop>
            <button @click="proofModal = false" class="absolute right-6 top-6 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
</x-layouts.admin>
