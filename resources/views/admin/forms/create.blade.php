<x-layouts.admin title="Buat Form">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.forms.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-semibold text-slate-800">Buat Form</h2>
            <p class="mt-0.5 text-sm text-slate-500">Isi detail form. Field bisa ditambahkan setelah form dibuat.</p>
        </div>
    </div>

    <div
        x-data="{ targetType: '{{ old('target_type', '') }}', submitting: false }"
        class="max-w-xl rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
    >
        <form method="POST" action="{{ route('admin.forms.store') }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Form</label>
                <input id="name" name="name" type="text" required maxlength="200" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Contoh: Pendaftaran Volunteer 2026">
                @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="banner_image" class="mb-1.5 block text-sm font-medium text-slate-700">Banner Form <span class="text-slate-400">(opsional)</span></label>
                <input id="banner_image" name="banner_image" type="file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                <p class="mt-1.5 text-xs text-slate-400">Gambar cover di atas form, mirip Google Forms. Maks 2MB.</p>
            </div>

            <div>
                <label for="font_family" class="mb-1.5 block text-sm font-medium text-slate-700">Font Form</label>
                <select id="font_family" name="font_family" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    @foreach (\App\Models\Form::FONTS as $value => $label)
                        <option value="{{ $value }}" @selected(old('font_family', 'sans') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="target_type" class="mb-1.5 block text-sm font-medium text-slate-700">Pasang ke</label>
                <select id="target_type" name="target_type" x-model="targetType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    <option value="">Form mandiri (berdiri sendiri)</option>
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
                        <option value="{{ $kelas->id }}" @selected(old('target_id') == $kelas->id)>{{ $kelas->title }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="targetType === 'event'" x-cloak>
                <label for="target_id_event" class="mb-1.5 block text-sm font-medium text-slate-700">Pilih Kegiatan</label>
                <select id="target_id_event" name="target_id" :disabled="targetType !== 'event'" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    <option value="">Pilih kegiatan...</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" @selected(old('target_id') == $event->id)>{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
            @error('target_id') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

            <div>
                <label for="notify_email" class="mb-1.5 block text-sm font-medium text-slate-700">Notifikasi Email <span class="text-slate-400">(opsional)</span></label>
                <input id="notify_email" name="notify_email" type="email" value="{{ old('notify_email') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="admin@ssic.id">
                <p class="mt-1.5 text-xs text-slate-400">Email ini akan menerima notifikasi tiap ada respons baru.</p>
            </div>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60">
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Buat Form & Lanjut Tambah Field'"></span>
                </button>
                <a href="{{ route('admin.forms.index') }}" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
