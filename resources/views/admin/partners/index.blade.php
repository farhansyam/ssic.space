<x-layouts.admin title="Mitra">
    <div
        x-data="{
            formModal: false,
            deleteModal: false,
            deleteAction: '',
            deleteName: '',
            logoPreview: null,
            onLogo(e) {
                const file = e.target.files[0];
                if (file) this.logoPreview = URL.createObjectURL(file);
            },
        }"
        @keydown.escape.window="formModal = false; deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Mitra</h2>
                <p class="mt-1 text-sm text-slate-500">Logo mitra/sponsor yang tampil di halaman utama.</p>
            </div>
            <button type="button" @click="formModal = true; logoPreview = null" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Mitra
            </button>
        </div>

        @if ($partners->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada mitra</p>
                <p class="mt-1 text-sm text-slate-500">Tambahkan logo mitra pertama lewat tombol di atas.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($partners as $partner)
                    <div class="group relative flex flex-col items-center gap-3 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="grid h-16 w-full place-items-center">
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="max-h-16 max-w-full object-contain">
                        </div>
                        <p class="truncate text-center text-xs font-semibold text-slate-600">{{ $partner->name }}</p>
                        <button type="button" @click="deleteModal = true; deleteAction = '{{ route('admin.partners.destroy', $partner) }}'; deleteName = '{{ $partner->name }}'" class="absolute right-2 top-2 grid h-7 w-7 place-items-center rounded-full bg-white text-slate-400 opacity-0 shadow-sm transition-all duration-200 hover:bg-rose-100 hover:text-rose-700 group-hover:opacity-100">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $partners->links() }}</div>
        @endif

        <!-- Create modal -->
        <div x-show="formModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="formModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="formModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="formModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="font-display text-lg font-semibold text-slate-800">Tambah Mitra</p>
                <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Logo</label>
                        <div class="flex items-center gap-4">
                            <div class="grid h-16 w-16 flex-shrink-0 place-items-center overflow-hidden rounded-xl bg-slate-100">
                                <img x-show="logoPreview" :src="logoPreview" class="h-full w-full object-contain">
                                <svg x-show="!logoPreview" class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 22.5H6a2.25 2.25 0 01-2.25-2.25V3.75A2.25 2.25 0 016 1.5h12a2.25 2.25 0 012.25 2.25v16.5A2.25 2.25 0 0118 22.5z" /></svg>
                            </div>
                            <input type="file" name="logo" accept="image/*" required @change="onLogo($event)" class="block flex-1 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Mitra</label>
                        <input type="text" name="name" required maxlength="150" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tautan (opsional)</label>
                        <input type="url" name="link" maxlength="255" placeholder="https://" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="formModal = false" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg">
                <p class="text-center font-display text-lg font-semibold text-slate-800">Hapus mitra?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">Logo <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen.</p>
                <div class="mt-6 flex gap-3">
                    <button @click="deleteModal = false" type="button" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Batal</button>
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
