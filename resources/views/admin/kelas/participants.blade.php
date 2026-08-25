<x-layouts.admin title="Peserta Kelas">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.kelas.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-semibold text-slate-800">Peserta: {{ $kelas->title }}</h2>
            <p class="mt-0.5 text-sm text-slate-500">{{ $registrations->total() }} pendaftar</p>
        </div>
    </div>

    @if ($registrations->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <p class="font-display text-lg font-semibold text-slate-700">Belum ada peserta</p>
            <p class="mt-1 text-sm text-slate-500">Peserta yang mendaftar akan muncul di sini.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Daftar Pada</th>
                        <th class="px-5 py-3 font-medium">Sertifikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($registrations as $reg)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-3.5 font-medium text-slate-700">{{ $reg->user->name }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $reg->user->email }}</td>
                            <td class="px-5 py-3.5">
                                <form
                                    method="POST"
                                    action="{{ route('admin.kelas.participants.status', [$kelas, $reg]) }}"
                                    x-data="{ submitting: false }"
                                    @submit="submitting = true"
                                >
                                    @csrf
                                    @method('PUT')
                                    <select
                                        name="status"
                                        :disabled="submitting"
                                        @change="$el.form.submit()"
                                        class="cursor-pointer rounded-full border-0 px-2.5 py-1 text-xs font-medium capitalize outline-none transition focus:ring-2 focus:ring-primary-300 disabled:cursor-wait disabled:opacity-60 {{ $reg->status === 'hadir' ? 'bg-emerald-100 text-emerald-700' : ($reg->status === 'batal' ? 'bg-rose-100 text-rose-700' : 'bg-primary-100 text-primary-700') }}"
                                    >
                                        <option value="terdaftar" @selected($reg->status === 'terdaftar')>Terdaftar</option>
                                        <option value="hadir" @selected($reg->status === 'hadir')>Hadir</option>
                                        <option value="batal" @selected($reg->status === 'batal')>Batal</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">{{ $reg->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-5 py-3.5">
                                @if ($certifiedUserIds->contains($reg->user_id))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Terbit
                                    </span>
                                @elseif ($reg->status === 'hadir')
                                    @if ($templates->isEmpty())
                                        <a href="{{ route('admin.certificate-templates.create') }}" class="text-xs font-medium text-primary-600 hover:underline">Buat template dulu</a>
                                    @else
                                        <form method="POST" action="{{ route('admin.kelas.participants.certificate', [$kelas, $reg]) }}" x-data="{ submitting: false }" @submit="submitting = true" class="flex items-center gap-1.5">
                                            @csrf
                                            <select name="template_id" required class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200">
                                                @foreach ($templates as $template)
                                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" :disabled="submitting" class="inline-flex items-center gap-1 rounded-lg bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700 transition hover:bg-primary-100 disabled:cursor-wait disabled:opacity-60">
                                                <span x-show="!submitting">Terbitkan</span>
                                                <span x-show="submitting">Memproses...</span>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-300">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $registrations->links() }}</div>
    @endif
</x-layouts.admin>
