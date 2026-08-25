@php
    $defaultFields = [
        'name' => ['label' => 'Nama Penerima', 'x' => 50, 'y' => 42, 'fontSize' => 32, 'color' => '#1e293b', 'bold' => true],
        'detail' => ['label' => 'Detail (Kelas/Kegiatan)', 'x' => 50, 'y' => 54, 'fontSize' => 16, 'color' => '#475569', 'bold' => false],
        'date' => ['label' => 'Tanggal', 'x' => 50, 'y' => 68, 'fontSize' => 13, 'color' => '#64748b', 'bold' => false],
        'number' => ['label' => 'Nomor Sertifikat', 'x' => 50, 'y' => 92, 'fontSize' => 11, 'color' => '#94a3b8', 'bold' => false],
    ];
    $existingLayout = $template->layout_json ?? null;
    $initialFields = $existingLayout ? array_replace_recursive($defaultFields, $existingLayout) : $defaultFields;
@endphp

<div
    x-data="{
        fields: {{ Illuminate\Support\Js::from($initialFields) }},
        active: 'name',
        dragging: null,
        imagePreview: {{ Illuminate\Support\Js::from($template->background_image ? Storage::url($template->background_image) : null) }},
        onImage(e) {
            const file = e.target.files[0];
            if (file) this.imagePreview = URL.createObjectURL(file);
        },
        startDrag(key, event) {
            event.preventDefault();
            this.active = key;
            this.dragging = key;
        },
        onDrag(event) {
            if (!this.dragging) return;
            const rect = this.$refs.canvas.getBoundingClientRect();
            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;
            let x = ((clientX - rect.left) / rect.width) * 100;
            let y = ((clientY - rect.top) / rect.height) * 100;
            x = Math.max(0, Math.min(100, x));
            y = Math.max(0, Math.min(100, y));
            this.fields[this.dragging].x = Math.round(x * 10) / 10;
            this.fields[this.dragging].y = Math.round(y * 10) / 10;
        },
        stopDrag() { this.dragging = null; },
        get layoutJson() { return JSON.stringify(this.fields); },
        previewing: false,
        showPreview: false,
        previewUrl: null,
        async previewPdf() {
            this.previewing = true;
            const form = this.$el.closest('form');
            const data = new FormData(form);
            data.delete('_method');
            try {
                const res = await fetch('{{ route('admin.certificate-templates.preview') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/pdf',
                    },
                    body: data,
                });
                if (!res.ok) {
                    alert('Gagal membuat preview. Pastikan background sudah diunggah.');
                    return;
                }
                const blob = await res.blob();
                if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
                this.previewUrl = URL.createObjectURL(blob);
                this.showPreview = true;
            } catch (e) {
                alert('Gagal membuat preview.');
            } finally {
                this.previewing = false;
            }
        },
        closePreview() {
            this.showPreview = false;
            if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
            this.previewUrl = null;
        },
    }"
    @mousemove.window="onDrag($event)"
    @mouseup.window="stopDrag()"
    @touchmove.window="onDrag($event)"
    @touchend.window="stopDrag()"
    class="space-y-6"
>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Template</label>
        <input type="text" name="name" value="{{ old('name', $template->name) }}" required maxlength="150" placeholder="Contoh: Sertifikat Kelas Reguler" class="w-full max-w-md rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Background Sertifikat {{ $template->exists ? '(kosongkan jika tidak ganti)' : '' }}</label>
        <input type="file" name="background_image" accept="image/*" {{ $template->exists ? '' : 'required' }} @change="onImage($event)" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
        <p class="mt-1.5 text-xs text-slate-400">Rasio disarankan A4 landscape (297:210). Ukuran maks 4MB.</p>
    </div>

    <input type="hidden" name="layout_json" :value="layoutJson">
    <input type="hidden" name="template_id" value="{{ $template->id ?? '' }}">

    <div>
        <p class="mb-2 text-sm font-medium text-slate-700">Atur Posisi Teks</p>
        <p class="mb-3 text-xs text-slate-400">Drag label di bawah untuk mengatur posisi masing-masing teks di atas background.</p>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <template x-if="!imagePreview">
                    <div class="flex aspect-[297/210] w-full items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 text-sm text-slate-400">
                        Upload background dulu untuk mulai atur posisi
                    </div>
                </template>
                <template x-if="imagePreview">
                    <div x-ref="canvas" class="relative aspect-[297/210] w-full select-none overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm" :style="`background-image:url(${imagePreview});background-size:cover;background-position:center;`">
                        <template x-for="(field, key) in fields" :key="key">
                            <div
                                class="absolute cursor-move touch-none rounded-lg px-2.5 py-1 text-xs font-semibold shadow-md ring-2 transition-shadow"
                                :class="active === key ? 'ring-primary-500 bg-primary-500 text-white' : 'ring-white/70 bg-white/90 text-slate-700 hover:ring-primary-300'"
                                :style="`left:${field.x}%; top:${field.y}%; transform: translate(-50%, -50%);`"
                                @mousedown="startDrag(key, $event)"
                                @touchstart="startDrag(key, $event)"
                                @click="active = key"
                            >
                                <span x-text="field.label"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="(field, key) in fields" :key="key">
                        <button type="button" @click="active = key" class="rounded-full px-3 py-1.5 text-xs font-semibold transition" :class="active === key ? 'bg-primary-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" x-text="field.label"></button>
                    </template>
                </div>

                <template x-for="(field, key) in fields" :key="'panel-'+key">
                    <div x-show="active === key" class="mt-4 space-y-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Ukuran Font (px)</label>
                            <input type="number" x-model.number="field.fontSize" min="8" max="80" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Warna Teks</label>
                            <input type="color" x-model="field.color" class="h-9 w-full rounded-lg border border-slate-200">
                        </div>
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                            <input type="checkbox" x-model="field.bold" class="rounded border-slate-300 text-primary-600 focus:ring-primary-400">
                            Tebal (bold)
                        </label>
                        <p class="text-xs text-slate-400">Posisi: <span x-text="field.x"></span>%, <span x-text="field.y"></span>%</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <a href="{{ route('admin.certificate-templates.index') }}" class="rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</a>
        <button
            type="button"
            @click="previewPdf()"
            :disabled="!imagePreview || previewing"
            class="inline-flex items-center gap-2 rounded-xl border border-primary-200 bg-white px-5 py-2.5 text-sm font-semibold text-primary-700 shadow-sm transition hover:bg-primary-50 disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-white"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span x-text="previewing ? 'Membuat preview...' : 'Preview PDF'"></span>
        </button>
        <button type="submit" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">Simpan Template</button>
    </div>

    <div x-show="showPreview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" style="display: none;">
        <div class="flex h-full max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" @click.outside="closePreview()">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                <p class="font-display text-sm font-semibold text-slate-700">Preview PDF Sertifikat</p>
                <div class="flex items-center gap-2">
                    <a :href="previewUrl" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-primary-600 hover:bg-primary-50">Buka di tab baru</a>
                    <button type="button" @click="closePreview()" class="grid h-8 w-8 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
            <iframe :src="previewUrl" class="flex-1 bg-slate-100"></iframe>
        </div>
    </div>
</div>
