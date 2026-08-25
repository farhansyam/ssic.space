<x-layouts.public
    :title="'Respons: '.$form->name"
    description="Hasil respons form (hanya-lihat, dibagikan lewat link)."
    :noindex="true"
>
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <div class="flex-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                    Dibagikan lewat link
                </span>
                <h1 class="mt-3 font-display text-2xl font-bold text-slate-800">Respons: {{ $form->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $submissions->total() }} respons masuk &middot; tampilan hanya-lihat, tanpa perlu login.</p>
            </div>
            <a href="{{ route('form.responses.export', $token) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Export CSV
            </a>
        </div>

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
    </div>
</x-layouts.public>
