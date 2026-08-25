<x-layouts.admin title="Sertifikat Terbit">
    <div
        x-data="{
            selected: [],
            allIds: {{ Illuminate\Support\Js::from($certificates->pluck('id')) }},
            get allChecked() { return this.allIds.length > 0 && this.selected.length === this.allIds.length; },
            toggleAll() { this.selected = this.allChecked ? [] : [...this.allIds]; },
        }"
    >
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.certificate-templates.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div class="flex-1">
                <h2 class="font-display text-2xl font-semibold text-slate-800">Sertifikat Terbit</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $certificates->total() }} sertifikat sudah diterbitkan.</p>
            </div>
            <form method="POST" action="{{ route('admin.certificates.download-batch') }}">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button
                    type="submit"
                    :disabled="selected.length === 0"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:translate-y-0"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    <span>Unduh Terpilih (<span x-text="selected.length"></span>) — ZIP</span>
                </button>
            </form>
        </div>

        @if ($certificates->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada sertifikat</p>
                <p class="mt-1 text-sm text-slate-500">Terbitkan sertifikat dari halaman peserta kelas/kegiatan.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="w-10 px-5 py-3">
                                <input type="checkbox" :checked="allChecked" @change="toggleAll()" class="rounded border-slate-300 text-primary-600 focus:ring-primary-400">
                            </th>
                            <th class="px-5 py-3 font-medium">Nomor</th>
                            <th class="px-5 py-3 font-medium">Penerima</th>
                            <th class="px-5 py-3 font-medium">Untuk</th>
                            <th class="px-5 py-3 font-medium">Diterbitkan</th>
                            <th class="px-5 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($certificates as $cert)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-3.5">
                                    @if ($cert->pdf_path)
                                        <input type="checkbox" value="{{ $cert->id }}" x-model.number="selected" class="rounded border-slate-300 text-primary-600 focus:ring-primary-400">
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-slate-500">{{ $cert->certificate_number }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-700">{{ $cert->user->name }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $cert->certifiable->title ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-400">{{ $cert->issued_at->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('sertifikat.verify', $cert->certificate_number) }}" target="_blank" class="text-xs font-medium text-slate-500 hover:text-primary-600">Verifikasi</a>
                                        @if ($cert->pdf_path)
                                            <a href="{{ Storage::url($cert->pdf_path) }}" target="_blank" class="text-xs font-semibold text-primary-600 hover:underline">Unduh PDF</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $certificates->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
