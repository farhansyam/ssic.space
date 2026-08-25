<x-layouts.admin title="Donasi">
    <div
        x-data="{ deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Campaign Donasi</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola campaign donasi &amp; fundraising.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.donations.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                    Konfirmasi Donasi
                </a>
                <a href="{{ route('admin.donation-campaigns.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Campaign
                </a>
            </div>
        </div>

        @if ($campaigns->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-primary-100">
                    <svg class="h-7 w-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.5-4.5-1.676 0-3.13.94-3.879 2.317h-1.242C10.63 4.19 9.176 3.25 7.5 3.25 5.099 3.25 3 5.265 3 7.75c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                </div>
                <p class="mt-4 font-display text-lg font-semibold text-slate-700">Belum ada campaign</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Mulai dengan menambahkan campaign donasi pertama.</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($campaigns as $campaign)
                    <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-32 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($campaign->image)
                                <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="truncate font-display font-semibold text-slate-800">{{ $campaign->title }}</h3>
                            <p class="mt-1 text-xs text-slate-400">Target Rp{{ number_format($campaign->target_amount, 0, ',', '.') }}</p>

                            <div class="mt-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-600">Rp{{ number_format($campaign->collectedAmount(), 0, ',', '.') }}</span>
                                    <span class="text-slate-400">{{ $campaign->progressPercent() }}%</span>
                                </div>
                                <div class="mt-1 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-primary-400 to-primary-600" style="width: {{ $campaign->progressPercent() }}%"></div>
                                </div>
                            </div>

                            <p class="mt-2.5 text-xs text-slate-400">{{ $campaign->donations_count }} donasi masuk</p>

                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3.5 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                <a href="{{ route('admin.donation-campaigns.disbursements.index', $campaign) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-sunny-100 hover:text-sunny-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.625c.621 0 1.125.504 1.125 1.125V6h-.75a.75.75 0 01-.75-.75v-.75m0 0H3.75m0 0v.75A.75.75 0 013 6m0 0h.75M3 6v6m18-6v6m0-6h-.75A.75.75 0 0119.5 5.25V4.5m0 15v-.75A.75.75 0 0120.25 18h.75m-1.5 0V6m0 12h-.75a.75.75 0 00-.75.75v.75" /></svg>
                                    Dana
                                </a>
                                <a href="{{ route('admin.donation-campaigns.edit', $campaign) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                    Edit
                                </a>
                                <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.donation-campaigns.destroy', $campaign) }}'; deleteName = '{{ $campaign->title }}'" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-rose-100 hover:text-rose-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $campaigns->links() }}</div>
        @endif

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-rose-100">
                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <p class="mt-4 text-center font-display text-lg font-semibold text-slate-800">Hapus campaign?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Campaign <span class="font-semibold" x-text="deleteName"></span> beserta seluruh data donasinya akan dihapus permanen.</p>
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
