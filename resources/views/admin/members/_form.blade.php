<div
    x-data="{
        submitting: false,
        preview: {{ Illuminate\Support\Js::from($member?->avatar ? Illuminate\Support\Facades\Storage::url($member->avatar) : null) }},
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
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="flex items-center gap-4">
            <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-full bg-slate-100">
                <template x-if="preview">
                    <img :src="preview" class="h-full w-full object-cover" alt="Preview">
                </template>
                <template x-if="!preview">
                    <svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </template>
            </div>
            <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                <span>Klik untuk pilih foto profil (JPG/PNG, maks 2MB)</span>
                <input type="file" name="avatar" accept="image/png,image/jpeg" @change="onFile" class="hidden">
            </label>
        </div>
        @error('avatar') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input
                    id="name" name="name" type="text" value="{{ old('name', $member->name ?? '') }}" required maxlength="150"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                    placeholder="Nama anggota"
                >
                @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                <input
                    id="email" name="email" type="email" value="{{ old('email', $member->email ?? '') }}" required maxlength="150"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                    placeholder="nama@email.com"
                >
                @error('email') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">No. HP <span class="text-slate-400">(opsional)</span></label>
                <input
                    id="phone" name="phone" type="text" value="{{ old('phone', $member->phone ?? '') }}" maxlength="20"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                    placeholder="08123456789"
                >
                @error('phone') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="division_id" class="mb-1.5 block text-sm font-medium text-slate-700">Divisi <span class="text-slate-400">(opsional)</span></label>
                <select
                    id="division_id" name="division_id"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                >
                    <option value="">— Tanpa Divisi —</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}" {{ (string) old('division_id', $member->division_id ?? '') === (string) $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                    @endforeach
                </select>
                @error('division_id') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Role</label>
            @if (auth()->user()->isSuperAdmin())
                <select
                    id="role" name="role"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                >
                    <option value="member" {{ old('role', $member->role ?? 'member') === 'member' ? 'selected' : '' }}>Member</option>
                    <option value="admin" {{ old('role', $member->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role', $member->role ?? '') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            @else
                <input type="hidden" name="role" value="{{ $member->role ?? 'member' }}">
                <p class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm capitalize text-slate-500">{{ str_replace('_', ' ', $member->role ?? 'member') }}</p>
                <p class="mt-1.5 text-xs text-slate-400">Hanya Super Admin yang bisa mengubah role.</p>
            @endif
        </div>

        <div>
            <label for="bio" class="mb-1.5 block text-sm font-medium text-slate-700">Bio <span class="text-slate-400">(opsional)</span></label>
            <textarea
                id="bio" name="bio" rows="3" maxlength="1000"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                placeholder="Ceritakan singkat tentang anggota ini..."
            >{{ old('bio', $member->bio ?? '') }}</textarea>
            @error('bio') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">
                    Password
                    <span class="text-slate-400">{{ $member ? '(kosongkan jika tidak diganti)' : '' }}</span>
                </label>
                <input
                    id="password" name="password" type="password" {{ $member ? '' : 'required' }} minlength="8"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                    placeholder="Minimal 8 karakter"
                >
                @error('password') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <input
                    id="password_confirmation" name="password_confirmation" type="password" {{ $member ? '' : 'required' }} minlength="8"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"
                    placeholder="Ulangi password"
                >
            </div>
        </div>

        <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
            <button
                type="submit"
                :disabled="submitting"
                class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
            >
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Akun'"></span>
            </button>
            <a href="{{ route('admin.members.index') }}" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">Batal</a>
        </div>
    </form>
</div>
