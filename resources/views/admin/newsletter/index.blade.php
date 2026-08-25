<x-layouts.admin title="Newsletter">
    <div
        x-data="{ deleteModal: false, deleteAction: '', deleteEmail: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Newsletter</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar subscriber newsletter &amp; WA blast.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <form method="GET">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari email..." class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                </form>
                <a href="{{ route('admin.newsletter.export') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-5 text-white shadow-playful">
                <p class="text-xs font-medium text-white/80">Total Subscriber</p>
                <p class="mt-1 font-display text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-700 to-primary-900 p-5 text-white shadow-playful">
                <p class="text-xs font-medium text-white/80">Aktif</p>
                <p class="mt-1 font-display text-2xl font-bold">{{ $stats['active'] }}</p>
            </div>
        </div>

        @if ($subscribers->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada subscriber</p>
                <p class="mt-1 text-sm text-slate-500">Subscriber baru akan muncul di sini setelah mendaftar lewat footer situs.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-5 py-3 font-medium">Email</th>
                            <th class="px-5 py-3 font-medium">No. HP</th>
                            <th class="px-5 py-3 font-medium">Subscribe Pada</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($subscribers as $subscriber)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-3.5 font-medium text-slate-700">{{ $subscriber->email }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $subscriber->phone ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-400">{{ $subscriber->subscribed_at?->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <form method="POST" action="{{ route('admin.newsletter.toggle', $subscriber) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full px-2.5 py-1 text-xs font-medium transition {{ $subscriber->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                            {{ $subscriber->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.newsletter.destroy', $subscriber) }}'; deleteEmail = '{{ $subscriber->email }}'" class="text-xs font-medium text-slate-400 transition hover:text-rose-600">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $subscribers->links() }}</div>
        @endif

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus subscriber?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500"><span class="font-semibold" x-text="deleteEmail"></span> akan dihapus dari daftar newsletter.</p>
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
