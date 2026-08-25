<div
    x-data="{
        submitting: false,
        preview: {{ Illuminate\Support\Js::from($popup?->image ? Illuminate\Support\Facades\Storage::url($popup->image) : null) }},
        onFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.preview = URL.createObjectURL(file);
        },
    }"
    class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
        @csrf
        @if ($method === 'PUT') @method('PUT') @endif

        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">Judul</label>
            <input id="title" name="title" type="text" required maxlength="200" value="{{ old('title', $popup->title ?? '') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
            @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="content" class="mb-1.5 block text-sm font-medium text-slate-700">Isi Pesan</label>
            <textarea id="content" name="content" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">{{ old('content', $popup->content ?? '') }}</textarea>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Gambar <span class="text-slate-400">(opsional, maks 2MB)</span></label>
            <div class="grid h-32 w-full place-items-center overflow-hidden rounded-xl bg-slate-100">
                <template x-if="preview"><img :src="preview" class="h-full w-full object-cover" alt="Preview"></template>
                <template x-if="!preview"><svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 3.75h-12v16.5h12V3.75z" /></svg></template>
            </div>
            <label class="mt-3 block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-2.5 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                <span>Klik untuk pilih gambar</span>
                <input type="file" name="image" accept="image/png,image/jpeg" @change="onFile" class="hidden">
            </label>
            @error('image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="cta_text" class="mb-1.5 block text-sm font-medium text-slate-700">Teks Tombol <span class="text-slate-400">(opsional)</span></label>
                <input id="cta_text" name="cta_text" type="text" maxlength="100" value="{{ old('cta_text', $popup->cta_text ?? '') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Donasi Sekarang">
            </div>
            <div>
                <label for="cta_link" class="mb-1.5 block text-sm font-medium text-slate-700">Link Tombol <span class="text-slate-400">(opsional)</span></label>
                <input id="cta_link" name="cta_link" type="text" maxlength="255" value="{{ old('cta_link', $popup->cta_link ?? '') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="/donasi">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="start_date" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Mulai <span class="text-slate-400">(opsional)</span></label>
                <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $popup?->start_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
            </div>
            <div>
                <label for="end_date" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Berakhir <span class="text-slate-400">(opsional)</span></label>
                <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $popup?->end_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                @error('end_date') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="show_frequency" class="mb-1.5 block text-sm font-medium text-slate-700">Frekuensi Tampil</label>
            <select id="show_frequency" name="show_frequency" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                <option value="once_per_session" @selected(old('show_frequency', $popup->show_frequency ?? 'once_per_session') === 'once_per_session')>Sekali per sesi</option>
                <option value="every_visit" @selected(old('show_frequency', $popup->show_frequency ?? '') === 'every_visit')>Setiap kunjungan</option>
            </select>
        </div>

        <label class="flex cursor-pointer items-center gap-2.5">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $popup->is_active ?? true)) class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
            <span class="text-sm text-slate-600">Aktifkan popup ini</span>
        </label>

        <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
            <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60">
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Popup'"></span>
            </button>
            <a href="{{ route('admin.popups.index') }}" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">Batal</a>
        </div>
    </form>
</div>
