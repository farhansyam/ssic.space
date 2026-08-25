<x-layouts.guest title="Daftar">
    <div
        x-data="{
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            password: '',
            password_confirmation: '',
            submitting: false,
            get emailValid() { return this.email === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email) },
            get passwordStrength() {
                if (this.password.length === 0) return 0;
                let score = 0;
                if (this.password.length >= 8) score++;
                if (/[A-Z]/.test(this.password)) score++;
                if (/[0-9]/.test(this.password)) score++;
                if (/[^A-Za-z0-9]/.test(this.password)) score++;
                return score;
            },
            get strengthLabel() {
                return ['Terlalu pendek', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'][this.passwordStrength];
            },
            get strengthColor() {
                return ['bg-slate-200', 'bg-rose-400', 'bg-sunny-400', 'bg-emerald-400', 'bg-emerald-600'][this.passwordStrength];
            },
            get confirmMatches() { return this.password_confirmation === '' || this.password === this.password_confirmation },
            get formValid() {
                return this.name && this.emailValid && this.email && this.password.length >= 8 && this.password === this.password_confirmation;
            },
        }"
    >
        <h1 class="font-display text-2xl font-semibold text-slate-800">Gabung SSIC</h1>
        <p class="mt-1 text-sm text-slate-500">Buat akun buat mulai ikut kelas, kegiatan, dan donasi bareng.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4" @submit="submitting = true">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama lengkap</label>
                <input
                    id="name" name="name" type="text" x-model="name" required autofocus
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                    placeholder="Nama kamu"
                >
                @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email" name="email" type="email" x-model="email" required
                        :class="!emailValid ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-200 focus:border-primary-500 focus:ring-primary-200'"
                        class="w-full rounded-xl border bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:ring-4"
                        placeholder="kamu@email.com"
                    >
                    <p x-show="!emailValid" x-cloak x-transition class="mt-1.5 text-xs text-rose-500">Format email belum valid.</p>
                    @error('email') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">No. HP <span class="text-slate-400">(opsional)</span></label>
                    <input
                        id="phone" name="phone" type="text" value="{{ old('phone') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                        placeholder="08xxxxxxxxxx"
                    >
                </div>
            </div>

            <div>
                <label for="division_id" class="mb-1.5 block text-sm font-medium text-slate-700">Divisi <span class="text-slate-400">(opsional)</span></label>
                <select
                    id="division_id" name="division_id"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                >
                    <option value="">Pilih divisi nanti saja</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}" @selected(old('division_id') == $division->id)>{{ $division->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                <input
                    id="password" name="password" type="password" x-model="password" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                    placeholder="Minimal 8 karakter"
                >
                <div x-show="password.length > 0" x-cloak x-transition class="mt-2">
                    <div class="flex gap-1">
                        <template x-for="i in 4" :key="i">
                            <div class="h-1.5 flex-1 rounded-full transition-colors duration-300" :class="i <= passwordStrength ? strengthColor : 'bg-slate-200'"></div>
                        </template>
                    </div>
                    <p class="mt-1 text-xs text-slate-500" x-text="strengthLabel"></p>
                </div>
                @error('password') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Ulangi password</label>
                <input
                    id="password_confirmation" name="password_confirmation" type="password" x-model="password_confirmation" required
                    :class="!confirmMatches ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-200 focus:border-primary-500 focus:ring-primary-200'"
                    class="w-full rounded-xl border bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:ring-4"
                    placeholder="Ulangi password"
                >
                <p x-show="!confirmMatches" x-cloak x-transition class="mt-1.5 text-xs text-rose-500">Password tidak sama.</p>
            </div>

            <button
                type="submit"
                :disabled="submitting || !formValid"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
            >
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="submitting ? 'Memproses...' : 'Daftar'"></span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Masuk di sini</a>
        </p>
    </div>
</x-layouts.guest>
