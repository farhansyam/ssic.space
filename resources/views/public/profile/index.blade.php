<x-layouts.public
    title="Profil Saya"
    description="Lihat progress, sertifikat, badge, dan riwayat kegiatanmu di SSIC."
>
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 px-4 py-14 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 overflow-hidden opacity-20">
            <div class="absolute -left-10 top-6 h-40 w-40 rounded-full bg-white blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-white blur-3xl"></div>
        </div>
        <div class="relative mx-auto flex max-w-4xl flex-col items-center gap-5 text-center sm:flex-row sm:text-left">
            <div class="grid h-24 w-24 shrink-0 place-items-center overflow-hidden rounded-full bg-white/15 font-display text-3xl font-bold text-white shadow-playful-lg ring-4 ring-white/30">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-1">
                <h1 class="font-display text-2xl font-bold text-white sm:text-3xl">{{ $user->name }}</h1>
                <p class="mt-1 text-sm text-primary-100">{{ $user->division->name ?? 'Belum ada divisi' }} &middot; Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-3 sm:justify-start">
                    <div class="rounded-xl bg-white/15 px-4 py-2 backdrop-blur">
                        <p class="text-xs text-primary-100">Poin</p>
                        <p class="font-display text-lg font-bold text-white">{{ $user->totalPoints() }}</p>
                    </div>
                    <div class="rounded-xl bg-white/15 px-4 py-2 backdrop-blur">
                        <p class="text-xs text-primary-100">Peringkat</p>
                        <p class="font-display text-lg font-bold text-white">#{{ $rank }}</p>
                    </div>
                    <div class="rounded-xl bg-white/15 px-4 py-2 backdrop-blur">
                        <p class="text-xs text-primary-100">Badge</p>
                        <p class="font-display text-lg font-bold text-white">{{ $badges->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-white/15 px-4 py-2 backdrop-blur">
                        <p class="text-xs text-primary-100">Sertifikat</p>
                        <p class="font-display text-lg font-bold text-white">{{ $certificates->count() }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('leaderboard') }}" class="shrink-0 rounded-xl border border-white/40 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                Lihat Leaderboard
            </a>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8" x-data="{ tab: 'progress' }">
        <div class="flex flex-wrap gap-2 border-b border-slate-100">
            @foreach (['progress' => 'Progress', 'riwayat' => 'Riwayat', 'pengaturan' => 'Pengaturan Akun'] as $key => $label)
                <button
                    type="button" @click="tab = '{{ $key }}'"
                    class="rounded-t-xl px-4 py-2.5 text-sm font-semibold transition"
                    :class="tab === '{{ $key }}' ? 'border-b-2 border-primary-500 text-primary-700' : 'text-slate-400 hover:text-slate-600'"
                >{{ $label }}</button>
            @endforeach
        </div>

        <!-- Progress tab -->
        <div x-show="tab === 'progress'" class="mt-8 space-y-10">
            <div>
                <h2 class="font-display text-lg font-semibold text-slate-800">Badge Kamu</h2>
                @if ($badges->isEmpty())
                    <p class="mt-3 rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-400">Belum ada badge. Aktif ikut kelas, kegiatan, atau donasi buat dapetin badge pertamamu!</p>
                @else
                    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($badges as $userBadge)
                            <div class="flex flex-col items-center rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm">
                                <span class="text-3xl">{{ $userBadge->badge->icon }}</span>
                                <p class="mt-2 font-display text-sm font-semibold text-slate-800">{{ $userBadge->badge->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $userBadge->earned_at?->translatedFormat('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="font-display text-lg font-semibold text-slate-800">Sertifikat Kamu</h2>
                @if ($certificates->isEmpty())
                    <p class="mt-3 rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-400">Belum ada sertifikat. Ikuti kelas/kegiatan sampai selesai buat dapetin sertifikat.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($certificates as $certificate)
                            <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:flex-nowrap">
                                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-display font-semibold text-slate-800">{{ $certificate->certifiable->title ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $certificate->certificate_number }} &middot; Terbit {{ $certificate->issued_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    @if ($certificate->pdf_path)
                                        <a href="{{ Storage::url($certificate->pdf_path) }}" target="_blank" class="rounded-lg bg-primary-50 px-3 py-1.5 text-xs font-semibold text-primary-700 transition hover:bg-primary-100">Unduh</a>
                                    @endif
                                    <a href="{{ route('sertifikat.verify', $certificate->certificate_number) }}" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">Verifikasi</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat tab -->
        <div x-show="tab === 'riwayat'" x-cloak class="mt-8 space-y-10">
            <div>
                <h2 class="font-display text-lg font-semibold text-slate-800">Riwayat Kelas</h2>
                @if ($classRegistrations->isEmpty())
                    <p class="mt-3 rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-400">Belum pernah daftar kelas.</p>
                @else
                    <div class="mt-4 divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                        @foreach ($classRegistrations as $reg)
                            <a href="{{ $reg->kelas ? route('kelas.show', $reg->kelas) : '#' }}" class="flex items-center justify-between gap-3 p-4 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-slate-800">{{ $reg->kelas->title ?? 'Kelas dihapus' }}</p>
                                    <p class="text-xs text-slate-400">{{ $reg->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium capitalize {{ $reg->status === 'hadir' ? 'bg-emerald-100 text-emerald-700' : ($reg->status === 'batal' ? 'bg-rose-100 text-rose-700' : 'bg-primary-100 text-primary-700') }}">{{ $reg->status }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h2 class="font-display text-lg font-semibold text-slate-800">Riwayat Kegiatan</h2>
                @if ($eventRegistrations->isEmpty())
                    <p class="mt-3 rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-400">Belum pernah daftar kegiatan.</p>
                @else
                    <div class="mt-4 divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                        @foreach ($eventRegistrations as $reg)
                            <a href="{{ $reg->event ? route('kegiatan.show', $reg->event) : '#' }}" class="flex items-center justify-between gap-3 p-4 transition hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-slate-800">{{ $reg->event->title ?? 'Kegiatan dihapus' }}</p>
                                    <p class="text-xs text-slate-400">{{ $reg->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium capitalize {{ $reg->status === 'hadir' ? 'bg-emerald-100 text-emerald-700' : ($reg->status === 'batal' ? 'bg-rose-100 text-rose-700' : 'bg-primary-100 text-primary-700') }}">{{ $reg->status }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Pengaturan tab -->
        <div x-show="tab === 'pengaturan'" x-cloak class="mt-8 space-y-6">
            <div
                x-data="{
                    submitting: false,
                    avatarPreview: {{ Illuminate\Support\Js::from($user->avatar ? Storage::url($user->avatar) : null) }},
                    onFile(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        this.avatarPreview = URL.createObjectURL(file);
                    },
                }"
                class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm"
            >
                <h3 class="font-display font-semibold text-slate-800">Edit Profil</h3>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" @submit="submitting = true" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-4">
                        <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-full bg-slate-100">
                            <template x-if="avatarPreview"><img :src="avatarPreview" class="h-full w-full object-cover"></template>
                            <template x-if="!avatarPreview"><span class="font-display text-lg font-bold text-slate-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span></template>
                        </div>
                        <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                            Ganti foto profil
                            <input type="file" name="avatar" accept="image/png,image/jpeg" @change="onFile" class="hidden">
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
                            <input id="name" name="name" type="text" required maxlength="150" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">No. HP</label>
                            <input id="phone" name="phone" type="text" maxlength="20" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                    </div>

                    <div>
                        <label for="division_id" class="mb-1.5 block text-sm font-medium text-slate-700">Divisi</label>
                        <select id="division_id" name="division_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            <option value="">— Tanpa Divisi —</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" @selected(old('division_id', $user->division_id) == $division->id)>{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="bio" class="mb-1.5 block text-sm font-medium text-slate-700">Bio</label>
                        <textarea id="bio" name="bio" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan Profil'"></span>
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" x-data="{ submitting: false }">
                <h3 class="font-display font-semibold text-slate-800">Ganti Password</h3>
                <form method="POST" action="{{ route('profile.password') }}" @submit="submitting = true" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        @error('current_password') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                            <input id="password" name="password" type="password" required minlength="8" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            @error('password') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        </div>
                    </div>
                    <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600 disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-text="submitting ? 'Menyimpan...' : 'Ganti Password'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>
