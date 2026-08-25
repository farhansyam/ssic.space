<x-layouts.admin title="Open Recruitment">
    <div
        x-data="{
            apps: {{ Illuminate\Support\Js::from($applications) }},
            columns: {{ Illuminate\Support\Js::from($columns) }},
            dragging: null,
            detailModal: false,
            detail: null,
            noteDraft: '',
            saving: false,
            byStatus(status) { return this.apps.filter(a => a.status === status); },
            openDetail(app) { this.detail = app; this.noteDraft = app.status_note || ''; this.detailModal = true; },
            async updateStatus(app, status, note = null) {
                this.saving = true;
                const res = await fetch(`/admin/recruitment/${app.id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status, status_note: note ?? app.status_note }),
                });
                if (res.ok) {
                    app.status = status;
                    if (note !== null) app.status_note = note;
                }
                this.saving = false;
            },
            onDrop(status) {
                if (this.dragging && this.dragging.status !== status) {
                    this.updateStatus(this.dragging, status);
                }
                this.dragging = null;
            },
        }"
        @keydown.escape.window="detailModal = false"
    >
        <div class="mb-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800">Open Recruitment</h2>
            <p class="mt-1 text-sm text-slate-500">Drag kartu pelamar antar kolom, atau klik kartu untuk lihat detail &amp; ubah status.</p>
        </div>

        @if (empty($applications) || $applications->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada pelamar</p>
                <p class="mt-1 text-sm text-slate-500">Buat form dengan tipe "Open Recruitment" di Form Builder agar respons masuk ke sini.</p>
                <a href="{{ route('admin.forms.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5">Buat Form Recruitment</a>
            </div>
        @else
            <div class="grid gap-4 lg:grid-cols-4">
                <template x-for="(label, status) in columns" :key="status">
                    <div
                        class="rounded-2xl bg-slate-100/70 p-3"
                        @dragover.prevent
                        @drop="onDrop(status)"
                    >
                        <div class="mb-3 flex items-center justify-between px-1">
                            <p class="text-sm font-semibold text-slate-600" x-text="label"></p>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-slate-500" x-text="byStatus(status).length"></span>
                        </div>
                        <div class="min-h-[120px] space-y-2.5">
                            <template x-for="app in byStatus(status)" :key="app.id">
                                <div
                                    draggable="true"
                                    @dragstart="dragging = app"
                                    @dragend="dragging = null"
                                    @click="openDetail(app)"
                                    class="cursor-pointer rounded-xl border border-slate-100 bg-white p-3.5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-playful active:cursor-grabbing"
                                    :class="dragging === app ? 'opacity-50' : ''"
                                >
                                    <p class="truncate text-sm font-semibold text-slate-800" x-text="app.applicant_name"></p>
                                    <p class="mt-0.5 truncate text-xs text-slate-400" x-text="app.applicant_email || app.form_name"></p>
                                    <p class="mt-2 truncate text-xs font-medium text-primary-600" x-text="app.form_name"></p>
                                    <p class="mt-1 text-[11px] text-slate-400" x-text="app.submitted_at"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        @endif

        <!-- Detail modal -->
        <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 grid place-items-center overflow-y-auto px-4 py-8">
            <div x-show="detailModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="detailModal = false" class="fixed inset-0 bg-slate-900/50"></div>
            <template x-if="detail">
                <div x-show="detailModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-playful-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-display text-lg font-semibold text-slate-800" x-text="detail.applicant_name"></p>
                            <p class="text-sm text-slate-400" x-text="detail.applicant_email"></p>
                        </div>
                        <button type="button" @click="detailModal = false" class="grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:bg-slate-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="mt-4 max-h-56 space-y-3 overflow-y-auto rounded-xl bg-slate-50 p-4">
                        <template x-for="answer in detail.answers" :key="answer.label">
                            <div>
                                <p class="text-xs font-medium text-slate-500" x-text="answer.label"></p>
                                <p class="text-sm text-slate-800" x-text="answer.value"></p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-5">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(label, status) in columns" :key="'btn-'+status">
                                <button
                                    type="button"
                                    @click="updateStatus(detail, status)"
                                    class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                                    :class="detail.status === status ? 'bg-primary-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    x-text="label"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Catatan Internal</label>
                        <textarea x-model="noteDraft" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Catatan untuk tim (tidak terlihat pelamar)"></textarea>
                    </div>

                    <div class="mt-5 flex gap-3">
                        <button type="button" @click="detailModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Tutup</button>
                        <button type="button" :disabled="saving" @click="updateStatus(detail, detail.status, noteDraft); detailModal = false" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 disabled:opacity-60">Simpan Catatan</button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-layouts.admin>
