<x-layouts.guest title="Masuk">
    <div
        x-data="{
            email: '{{ old('email') }}',
            password: '',
            submitting: false,
            get emailValid() { return this.email === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email) },
        }"
    >
        <h1 class="font-display text-2xl font-semibold text-slate-800">Selamat datang kembali</h1>
        <p class="mt-1 text-sm text-slate-500">Masuk untuk lanjut ikut kegiatan &amp; kelas SSIC.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" @submit="submitting = true">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                <input
                    id="email" name="email" type="email" x-model="email" required autofocus
                    :class="!emailValid ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-200 focus:border-primary-500 focus:ring-primary-200'"
                    class="w-full rounded-xl border bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:ring-4"
                    placeholder="kamu@email.com"
                >
                <p x-show="!emailValid" x-cloak x-transition class="mt-1.5 text-xs text-rose-500">Format email belum valid.</p>
                @error('email') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                <input
                    id="password" name="password" type="password" x-model="password" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                    placeholder="••••••••"
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-500 focus:ring-primary-400">
                    Ingat saya
                </label>
            </div>

            <button
                type="submit"
                :disabled="submitting || !email || !password"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-4 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
            >
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="submitting ? 'Memproses...' : 'Masuk'"></span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Daftar sekarang</a>
        </p>
    </div>
</x-layouts.guest>
