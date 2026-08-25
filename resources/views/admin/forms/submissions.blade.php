<x-layouts.admin title="Respons Form">
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.forms.edit', $form) }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div class="flex-1">
            <h2 class="font-display text-2xl font-semibold text-slate-800">Respons: {{ $form->name }}</h2>
            <p class="mt-0.5 text-sm text-slate-500">{{ $submissions->total() }} respons masuk.</p>
        </div>
        <a href="{{ route('admin.forms.export', $form) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Export CSV
        </a>
        @if (! $form->share_token)
            <form method="POST" action="{{ route('admin.forms.share.enable', $form) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" /></svg>
                    Bagikan ke Publik
                </button>
            </form>
        @endif
    </div>

    @if ($form->share_token)
        <div x-data="{ copied: false }" class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-primary-100 bg-primary-50 px-5 py-4">
            <svg class="h-5 w-5 shrink-0 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-primary-800">Link publik aktif — siapa saja bisa lihat &amp; unduh respons ini tanpa login.</p>
                <p class="truncate text-xs text-primary-600">{{ route('form.responses', $form->share_token) }}</p>
            </div>
            <button
                type="button"
                @click="navigator.clipboard.writeText('{{ route('form.responses', $form->share_token) }}'); copied = true; setTimeout(() => copied = false, 1500)"
                class="shrink-0 rounded-xl bg-white px-3 py-2 text-xs font-semibold text-primary-700 shadow-sm transition hover:bg-primary-100"
            >
                <span x-show="!copied">Salin Link</span>
                <span x-show="copied" x-cloak>Tersalin!</span>
            </button>
            <form method="POST" action="{{ route('admin.forms.share.disable', $form) }}">
                @csrf
                <button type="submit" class="shrink-0 rounded-xl px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Nonaktifkan</button>
            </form>
        </div>
    @endif

    @if ($submissions->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <p class="font-display text-lg font-semibold text-slate-700">Belum ada respons</p>
            <p class="mt-1 text-sm text-slate-500">Respons yang masuk akan muncul di sini.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-slate-100 bg-white shadow-sm">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        @foreach ($form->fields as $field)
                            <th class="px-5 py-3 font-medium">{{ $field->label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($submissions as $submission)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-400">{{ $submission->created_at->translatedFormat('d M Y, H:i') }}</td>
                            @foreach ($form->fields as $field)
                                @php $value = $submission->data_json[$field->id] ?? null; @endphp
                                <td class="px-5 py-3.5 text-slate-700">
                                    @if ($field->type === 'audio' && $value)
                                        <audio controls preload="none" class="h-9 w-56"><source src="{{ Storage::url($value) }}"></audio>
                                    @elseif ($field->type === 'file' && $value)
                                        <a href="{{ Storage::url($value) }}" target="_blank" class="font-medium text-primary-600 hover:underline">Lihat File</a>
                                    @elseif (is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value ?: '—' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $submissions->links() }}</div>
    @endif
</x-layouts.admin>
