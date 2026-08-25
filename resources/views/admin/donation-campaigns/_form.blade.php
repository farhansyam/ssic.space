<div
    x-data="{
        title: {{ Illuminate\Support\Js::from(old('title', $campaign->title ?? '')) }},
        description: {{ Illuminate\Support\Js::from(old('description', $campaign->description ?? '')) }},
        submitting: false,
        preview: {{ Illuminate\Support\Js::from($campaign?->image ? Illuminate\Support\Facades\Storage::url($campaign->image) : null) }},
        onFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.preview = URL.createObjectURL(file);
        },
        get slugPreview() {
            return this.title.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'slug-otomatis';
        },
    }"
    class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
        @csrf
        @if ($method === 'PUT') @method('PUT') @endif

        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">Judul Campaign</label>
            <input id="title" name="title" type="text" x-model="title" required maxlength="200" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Bantu Renovasi Rumah Singgah">
            <p class="mt-1.5 text-xs text-slate-400">Slug: <span class="font-mono text-slate-500" x-text="slugPreview"></span></p>
            @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                <span class="text-xs text-slate-400" x-text="description.length + ' karakter'"></span>
            </div>
            <textarea id="description" name="description" rows="4" x-model="description" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Ceritakan tujuan campaign ini..."></textarea>
            @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="target_amount" class="mb-1.5 block text-sm font-medium text-slate-700">Target Nominal (Rp)</label>
                <input id="target_amount" name="target_amount" type="number" min="0" step="1000" value="{{ old('target_amount', $campaign->target_amount ?? '') }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="10000000">
                @error('target_amount') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="deadline" class="mb-1.5 block text-sm font-medium text-slate-700">Tenggat Waktu <span class="text-slate-400">(opsional)</span></label>
                <input id="deadline" name="deadline" type="date" value="{{ old('deadline', $campaign?->deadline?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                @error('deadline') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Foto Campaign <span class="text-slate-400">(opsional, maks 2MB)</span></label>
            <div class="flex items-center gap-4">
                <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-2xl bg-slate-100">
                    <template x-if="preview"><img :src="preview" class="h-full w-full object-cover" alt="Preview"></template>
                    <template x-if="!preview"><svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 3.75h-12v16.5h12V3.75z" /></svg></template>
                </div>
                <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                    <span>Klik untuk pilih gambar (JPG/PNG)</span>
                    <input type="file" name="image" accept="image/png,image/jpeg" @change="onFile" class="hidden">
                </label>
            </div>
            @error('image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @include('admin._seo-fields', ['seoable' => $campaign])

        <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
            <button type="submit" :disabled="submitting || !title" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Campaign'"></span>
            </button>
            <a href="{{ route('admin.donation-campaigns.index') }}" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">Batal</a>
        </div>
    </form>
</div>
