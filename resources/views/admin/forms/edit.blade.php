<x-layouts.admin title="Edit Form">
    <div
        x-data="{
            fields: {{ Illuminate\Support\Js::from($form->fields) }},
            fieldModal: false,
            editingField: null,
            fieldLabel: '',
            fieldType: 'text',
            fieldOptions: '',
            fieldRequired: false,
            submittingField: false,
            deleteModal: false,
            deleteTarget: null,
            dragIndex: null,
            overIndex: null,
            previewModal: false,
            submitting: false,
            targetType: {{ Illuminate\Support\Js::from(old('target_type', $form->target_type ?? '')) }},
            name: {{ Illuminate\Support\Js::from(old('name', $form->name)) }},
            description: {{ Illuminate\Support\Js::from(old('description', $form->description)) }},
            fontFamily: {{ Illuminate\Support\Js::from(old('font_family', $form->font_family)) }},
            themeColor: {{ Illuminate\Support\Js::from(old('theme_color', $form->theme_color ?: '#2474D2')) }},
            bannerPreview: {{ Illuminate\Support\Js::from($form->banner_image ? Storage::url($form->banner_image) : null) }},
            removeBanner: false,
            fontStyles: {
                sans: `font-family: var(--font-sans);`,
                playful: `font-family: var(--font-display);`,
                serif: `font-family: Georgia, 'Times New Roman', serif;`,
                mono: `font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, 'Liberation Mono', monospace;`,
            },
            typeLabels: {
                text: 'Teks Pendek', textarea: 'Teks Panjang', email: 'Email', phone: 'No. HP',
                select: 'Dropdown', radio: 'Pilihan Tunggal', checkbox: 'Centang', file: 'Upload File', date: 'Tanggal', audio: 'Upload Audio (MP3)',
            },
            needsOptions() { return ['select', 'radio', 'checkbox'].includes(this.fieldType); },
            openAddField() {
                this.editingField = null; this.fieldLabel = ''; this.fieldType = 'text'; this.fieldOptions = ''; this.fieldRequired = false;
                this.fieldModal = true;
            },
            openEditField(field) {
                this.editingField = field; this.fieldLabel = field.label; this.fieldType = field.type;
                this.fieldOptions = (field.options_json || []).join('\n'); this.fieldRequired = !!field.is_required;
                this.fieldModal = true;
            },
            csrf() { return document.querySelector('meta[name=csrf-token]').content; },
            async saveField() {
                this.submittingField = true;
                const payload = {
                    label: this.fieldLabel,
                    type: this.fieldType,
                    is_required: this.fieldRequired ? 1 : 0,
                    options: this.fieldOptions.split('\n').map(s => s.trim()).filter(Boolean),
                };
                const url = this.editingField
                    ? `{{ route('admin.forms.fields.store', $form) }}/${this.editingField.id}`
                    : `{{ route('admin.forms.fields.store', $form) }}`;
                const res = await fetch(url, {
                    method: this.editingField ? 'PUT' : 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (this.editingField) {
                    this.fields = this.fields.map(f => f.id === data.id ? data : f);
                } else {
                    this.fields.push(data);
                }
                this.submittingField = false;
                this.fieldModal = false;
            },
            confirmDelete(field) { this.deleteTarget = field; this.deleteModal = true; },
            async deleteField() {
                await fetch(`{{ route('admin.forms.fields.store', $form) }}/${this.deleteTarget.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                });
                this.fields = this.fields.filter(f => f.id !== this.deleteTarget.id);
                this.deleteModal = false;
            },
            onDrop(index) {
                if (this.dragIndex === null || this.dragIndex === index) { this.overIndex = null; return; }
                const item = this.fields.splice(this.dragIndex, 1)[0];
                this.fields.splice(index, 0, item);
                this.dragIndex = null;
                this.overIndex = null;
                this.persistOrder();
            },
            async persistOrder() {
                await fetch(`{{ route('admin.forms.fields.reorder', $form) }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ order: this.fields.map(f => f.id) }),
                });
            },
            onBanner(e) {
                const file = e.target.files[0];
                if (file) { this.bannerPreview = URL.createObjectURL(file); this.removeBanner = false; }
            },
        }"
        @keydown.escape.window="fieldModal = false; deleteModal = false; previewModal = false"
    >
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.forms.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">{{ $form->name }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">Kelola detail form dan susun field pendaftaran.</p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" @click="previewModal = true" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Preview
                </button>
                <a href="{{ route('form.show', $form) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                    Form Tersimpan
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Field builder -->
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-display font-semibold text-slate-800">Field Form</h3>
                        <button type="button" @click="openAddField()" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2 text-xs font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Tambah Field
                        </button>
                    </div>

                    <p x-show="fields.length === 0" class="rounded-xl border border-dashed border-slate-200 py-10 text-center text-sm text-slate-400">Belum ada field. Klik "Tambah Field" untuk mulai.</p>

                    <div class="space-y-2.5" x-show="fields.length > 0">
                        <template x-for="(field, index) in fields" :key="field.id">
                            <div
                                draggable="true"
                                @dragstart="dragIndex = index"
                                @dragover.prevent="overIndex = index"
                                @dragleave="overIndex = overIndex === index ? null : overIndex"
                                @drop.prevent="onDrop(index)"
                                @dragend="dragIndex = null; overIndex = null"
                                class="flex cursor-grab items-center gap-3 rounded-xl border bg-white p-3.5 transition active:cursor-grabbing"
                                :class="overIndex === index ? 'border-primary-400 bg-primary-50' : 'border-slate-100'"
                            >
                                <svg class="h-5 w-5 shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-slate-700" x-text="field.label"></p>
                                    <p class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-400">
                                        <span x-text="typeLabels[field.type]"></span>
                                        <template x-if="field.is_required"><span class="rounded-full bg-rose-50 px-1.5 py-0.5 font-medium text-rose-500">wajib</span></template>
                                    </p>
                                </div>
                                <button type="button" @click="openEditField(field)" class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-primary-50 hover:text-primary-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                </button>
                                <button type="button" @click="confirmDelete(field)" class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-rose-100 hover:text-rose-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <p class="mt-4 text-xs text-slate-400" x-show="fields.length > 1">Seret <span class="font-semibold">&#9776;</span> untuk mengurutkan ulang field.</p>
                </div>

                <!-- Add/Edit field modal -->
                <div x-show="fieldModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
                    <div x-show="fieldModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="fieldModal = false" class="absolute inset-0 bg-slate-900/50"></div>
                    <div x-show="fieldModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-playful-lg">
                        <p class="font-display text-lg font-semibold text-slate-800" x-text="editingField ? 'Edit Field' : 'Tambah Field'"></p>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Label</label>
                                <input type="text" x-model="fieldLabel" maxlength="200" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Nama Lengkap">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Tipe Field</label>
                                <select x-model="fieldType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                    <template x-for="(label, value) in typeLabels" :key="value">
                                        <option :value="value" x-text="label"></option>
                                    </template>
                                </select>
                            </div>
                            <div x-show="needsOptions()" x-cloak>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Pilihan <span class="text-slate-400">(satu per baris)</span></label>
                                <textarea x-model="fieldOptions" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Opsi 1&#10;Opsi 2&#10;Opsi 3"></textarea>
                            </div>
                            <label class="flex cursor-pointer items-center gap-2.5">
                                <input type="checkbox" x-model="fieldRequired" class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
                                <span class="text-sm text-slate-600">Wajib diisi</span>
                            </label>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="button" @click="fieldModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                            <button type="button" @click="saveField()" :disabled="submittingField || !fieldLabel" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60">
                                <span x-text="submittingField ? 'Menyimpan...' : 'Simpan'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delete field modal -->
                <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
                    <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
                    <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                        <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus field ini?</p>
                        <p class="mt-1.5 text-center text-sm text-slate-500" x-text="'Field &quot;' + (deleteTarget?.label ?? '') + '&quot; akan dihapus permanen.'"></p>
                        <div class="mt-6 flex gap-3">
                            <button @click="deleteModal = false" type="button" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                            <button @click="deleteField()" type="button" class="flex-1 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">Ya, Hapus</button>
                        </div>
                    </div>
                </div>

                <!-- Live preview (inline) -->
                <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <p class="mb-3 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Live Preview
                    </p>
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <template x-if="bannerPreview">
                            <img :src="bannerPreview" class="h-28 w-full object-cover">
                        </template>
                        <div class="p-5" :style="fontStyles[fontFamily] || fontStyles.sans">
                            <h3 class="font-display text-lg font-bold text-slate-800" x-text="name || 'Nama Form'"></h3>
                            <p class="mt-1 text-sm text-slate-500" x-text="description"></p>

                            <div class="mt-4 space-y-3">
                                <template x-for="field in fields" :key="field.id">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-slate-600" x-text="field.label + (field.is_required ? ' *' : '')"></label>
                                        <template x-if="field.type === 'textarea'">
                                            <textarea disabled rows="2" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-400"></textarea>
                                        </template>
                                        <template x-if="field.type === 'select'">
                                            <select disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-400">
                                                <option>Pilih...</option>
                                            </select>
                                        </template>
                                        <template x-if="['radio', 'checkbox'].includes(field.type)">
                                            <div class="space-y-1">
                                                <template x-for="option in (field.options_json && field.options_json.length ? field.options_json : ['Opsi 1'])" :key="option">
                                                    <label class="flex items-center gap-1.5 text-xs text-slate-400">
                                                        <input :type="field.type" disabled>
                                                        <span x-text="option"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="['file', 'audio'].includes(field.type)">
                                            <div class="rounded-lg border-2 border-dashed border-slate-200 px-3 py-2 text-center text-xs text-slate-400" x-text="field.type === 'audio' ? 'Upload audio MP3' : 'Upload file'"></div>
                                        </template>
                                        <template x-if="!['textarea', 'select', 'radio', 'checkbox', 'file', 'audio'].includes(field.type)">
                                            <input disabled type="text" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-400">
                                        </template>
                                    </div>
                                </template>
                                <p x-show="fields.length === 0" class="text-xs text-slate-400">Belum ada field untuk dipratinjau.</p>
                            </div>

                            <div class="mt-4 w-full rounded-lg py-2 text-center text-xs font-semibold text-white" :style="`background-color: ${themeColor}`">Kirim</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form settings -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Pengaturan Form</h3>
                <form method="POST" action="{{ route('admin.forms.update', $form) }}" enctype="multipart/form-data" @submit="submitting = true" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Form</label>
                        <input id="name" name="name" type="text" x-model="name" required maxlength="200" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description" x-model="description" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Banner Form</label>
                        <template x-if="bannerPreview">
                            <div class="relative mb-2 overflow-hidden rounded-xl">
                                <img :src="bannerPreview" class="h-24 w-full object-cover">
                                <button type="button" @click="bannerPreview = null; removeBanner = true" class="absolute right-2 top-2 grid h-6 w-6 place-items-center rounded-full bg-slate-900/60 text-white hover:bg-rose-600">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </template>
                        <input type="hidden" name="remove_banner" :value="removeBanner ? '1' : '0'">
                        <input id="banner_image" name="banner_image" type="file" accept="image/*" @change="onBanner($event)" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1.5 text-xs text-slate-400">Gambar cover di atas form, mirip Google Forms. Maks 2MB.</p>
                    </div>

                    <div>
                        <label for="font_family" class="mb-1.5 block text-sm font-medium text-slate-700">Font Form</label>
                        <select id="font_family" name="font_family" x-model="fontFamily" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            @foreach (\App\Models\Form::FONTS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="theme_color" class="mb-1.5 block text-sm font-medium text-slate-700">Warna Aksen Form</label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="themeColor" class="h-11 w-14 cursor-pointer rounded-lg border border-slate-200 bg-white p-1">
                            <input id="theme_color" name="theme_color" type="text" x-model="themeColor" maxlength="7" class="w-32 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                        @error('theme_color') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="target_type" class="mb-1.5 block text-sm font-medium text-slate-700">Pasang ke</label>
                        <select id="target_type" name="target_type" x-model="targetType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            <option value="">Form mandiri</option>
                            <option value="kelas">Pendaftaran Kelas</option>
                            <option value="event">Pendaftaran Kegiatan</option>
                            <option value="recruitment">Open Recruitment</option>
                        </select>
                        <p x-show="targetType === 'recruitment'" x-cloak class="mt-1.5 text-xs text-slate-400">Respons form otomatis masuk ke papan Recruitment (submitted &rarr; interview &rarr; diterima/ditolak).</p>
                    </div>

                    <div x-show="targetType === 'kelas'" x-cloak>
                        <label for="target_id_kelas" class="mb-1.5 block text-sm font-medium text-slate-700">Pilih Kelas</label>
                        <select id="target_id_kelas" name="target_id" :disabled="targetType !== 'kelas'" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            <option value="">Pilih kelas...</option>
                            @foreach ($classes as $kelas)
                                <option value="{{ $kelas->id }}" @selected($form->target_type === 'kelas' && $form->target_id === $kelas->id)>{{ $kelas->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="targetType === 'event'" x-cloak>
                        <label for="target_id_event" class="mb-1.5 block text-sm font-medium text-slate-700">Pilih Kegiatan</label>
                        <select id="target_id_event" name="target_id" :disabled="targetType !== 'event'" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            <option value="">Pilih kegiatan...</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" @selected($form->target_type === 'event' && $form->target_id === $event->id)>{{ $event->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('target_id') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

                    <div>
                        <label for="notify_email" class="mb-1.5 block text-sm font-medium text-slate-700">Notifikasi Email</label>
                        <input id="notify_email" name="notify_email" type="email" value="{{ old('notify_email', $form->notify_email) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-sm font-semibold text-slate-700">Halaman Setelah Isi</p>
                        <p class="mt-0.5 text-xs text-slate-400">Tampil ke pengisi setelah form berhasil dikirim.</p>

                        <div class="mt-3">
                            <label for="confirmation_title" class="mb-1.5 block text-sm font-medium text-slate-700">Judul</label>
                            <input id="confirmation_title" name="confirmation_title" type="text" maxlength="200" value="{{ old('confirmation_title', $form->confirmation_title) }}" placeholder="Terima kasih!" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>

                        <div class="mt-3">
                            <label for="confirmation_message" class="mb-1.5 block text-sm font-medium text-slate-700">Pesan</label>
                            <textarea id="confirmation_message" name="confirmation_message" rows="2" maxlength="1000" placeholder="Responsmu sudah kami terima." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">{{ old('confirmation_message', $form->confirmation_message) }}</textarea>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div>
                                <label for="confirmation_link_url" class="mb-1.5 block text-sm font-medium text-slate-700">Link <span class="text-slate-400">(opsional)</span></label>
                                <input id="confirmation_link_url" name="confirmation_link_url" type="url" maxlength="500" value="{{ old('confirmation_link_url', $form->confirmation_link_url) }}" placeholder="https://wa.me/..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                @error('confirmation_link_url') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="confirmation_link_label" class="mb-1.5 block text-sm font-medium text-slate-700">Teks Tombol</label>
                                <input id="confirmation_link_label" name="confirmation_link_label" type="text" maxlength="100" value="{{ old('confirmation_link_label', $form->confirmation_link_label) }}" placeholder="Join Grup WA" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                @error('confirmation_link_label') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
                    </button>
                </form>

                <div class="mt-4 border-t border-slate-100 pt-4">
                    <a href="{{ route('admin.forms.submissions', $form) }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                        Lihat {{ $form->submissions()->count() }} Respons
                    </a>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4" x-data="{ copied: false }">
                    <p class="mb-2 text-sm font-semibold text-slate-700">Bagikan Respons</p>
                    <p class="mb-2 text-xs text-slate-400">Seperti share spreadsheet Google Form — siapa saja yang punya link bisa lihat &amp; unduh respons tanpa login.</p>
                    @if ($form->share_token)
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('form.responses', $form->share_token) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600 outline-none">
                            <button
                                type="button"
                                @click="navigator.clipboard.writeText('{{ route('form.responses', $form->share_token) }}'); copied = true; setTimeout(() => copied = false, 1500)"
                                class="shrink-0 rounded-xl bg-primary-50 px-3 py-2 text-xs font-semibold text-primary-700 transition hover:bg-primary-100"
                            >
                                <span x-show="!copied">Salin</span>
                                <span x-show="copied" x-cloak>Tersalin!</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('admin.forms.share.disable', $form) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-rose-600 hover:underline">Nonaktifkan link berbagi</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.forms.share.enable', $form) }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" /></svg>
                                Aktifkan Link Berbagi Respons
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4" x-data="{ copied: false }">
                    <p class="mb-2 text-sm font-semibold text-slate-700">Short Link</p>
                    @if ($shortLink)
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ url('/'.$shortLink->slug) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600 outline-none">
                            <button
                                type="button"
                                @click="navigator.clipboard.writeText('{{ url('/'.$shortLink->slug) }}'); copied = true; setTimeout(() => copied = false, 1500)"
                                class="shrink-0 rounded-xl bg-primary-50 px-3 py-2 text-xs font-semibold text-primary-700 transition hover:bg-primary-100"
                            >
                                <span x-show="!copied">Salin</span>
                                <span x-show="copied" x-cloak>Tersalin!</span>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">{{ $shortLink->click_count }} klik &middot; <a href="{{ route('admin.short-links.index') }}" class="text-primary-600 hover:underline">Kelola di Short Link</a></p>
                    @else
                        <form method="POST" action="{{ route('admin.forms.short-link', $form) }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                Buat Short Link untuk Form Ini
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fullscreen Google-Forms style preview modal -->
        <div x-show="previewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-100">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-200 bg-white px-6 py-3 shadow-sm">
                <p class="font-display font-semibold text-slate-800">Preview: <span x-text="name"></span></p>
                <button @click="previewModal = false" type="button" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="mx-auto max-w-2xl px-4 py-10">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-playful-lg">
                    <template x-if="bannerPreview">
                        <img :src="bannerPreview" class="h-48 w-full object-cover">
                    </template>
                    <div class="p-8" :style="fontStyles[fontFamily] || fontStyles.sans">
                        <h1 class="font-display text-2xl font-bold text-slate-800" x-text="name || 'Nama Form'"></h1>
                        <p class="mt-2 text-slate-500" x-text="description"></p>

                        <div class="mt-8 space-y-5">
                            <template x-for="field in fields" :key="field.id">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-slate-700" x-text="field.label + (field.is_required ? ' *' : '')"></label>
                                    <template x-if="field.type === 'textarea'">
                                        <textarea disabled rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-400"></textarea>
                                    </template>
                                    <template x-if="field.type === 'select'">
                                        <select disabled class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-400">
                                            <option>Pilih...</option>
                                            <template x-for="option in (field.options_json || [])" :key="option"><option x-text="option"></option></template>
                                        </select>
                                    </template>
                                    <template x-if="['radio', 'checkbox'].includes(field.type)">
                                        <div class="space-y-2">
                                            <template x-for="option in (field.options_json && field.options_json.length ? field.options_json : ['Opsi 1'])" :key="option">
                                                <label class="flex cursor-not-allowed items-center gap-2.5 rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-400">
                                                    <input :type="field.type" disabled>
                                                    <span x-text="option"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="['file', 'audio'].includes(field.type)">
                                        <div class="rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-400" x-text="field.type === 'audio' ? 'Upload audio (MP3/WAV/OGG)' : 'Upload file'"></div>
                                    </template>
                                    <template x-if="!['textarea', 'select', 'radio', 'checkbox', 'file', 'audio'].includes(field.type)">
                                        <input disabled :type="field.type === 'email' ? 'email' : (field.type === 'date' ? 'date' : 'text')" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-400">
                                    </template>
                                </div>
                            </template>
                            <p x-show="fields.length === 0" class="text-sm text-slate-400">Belum ada field untuk dipratinjau.</p>
                        </div>

                        <div class="mt-8 w-full rounded-xl px-6 py-3 text-center text-sm font-semibold text-white shadow-playful" :style="`background-color: ${themeColor}`">Kirim</div>
                        <p class="mt-3 text-center text-xs text-slate-400">Ini tampilan pratinjau — data tidak akan terkirim.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
