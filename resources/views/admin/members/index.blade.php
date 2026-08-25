<x-layouts.admin title="Anggota">
    <div
        x-data="{ deleteModal: false, deleteAction: '', deleteName: '' }"
        @keydown.escape.window="deleteModal = false"
    >
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-800">Anggota</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola data anggota &amp; akun pengelola SSIC.</p>
            </div>
            <a
                href="{{ route('admin.members.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Akun
            </a>
        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Total Akun</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Anggota</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['members'] }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-primary-700 to-primary-900 p-5 text-white shadow-playful">
                <p class="text-sm font-medium text-white/85">Admin &amp; Super Admin</p>
                <p class="mt-1 font-display text-3xl font-bold">{{ $stats['admins'] }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.members.index') }}" class="mb-6 flex flex-wrap items-center gap-3">
            <div class="relative max-w-sm flex-1">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input
                    type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200"
                >
            </div>
            <select name="role" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                <option value="">Semua Role</option>
                <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            </select>
            <select name="division_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:ring-4 focus:ring-primary-200">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" {{ (string) request('division_id') === (string) $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                @endforeach
            </select>
            @if (request('q') || request('role') || request('division_id'))
                <a href="{{ route('admin.members.index') }}" class="text-sm font-medium text-slate-400 hover:text-slate-600">Reset</a>
            @endif
        </form>

        @if ($members->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-primary-100">
                    <svg class="h-7 w-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                </div>
                <p class="mt-4 font-display text-lg font-semibold text-slate-700">Tidak ada anggota yang cocok</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Coba ubah kata kunci pencarian atau filter.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Anggota</th>
                                <th class="px-5 py-3">Divisi</th>
                                <th class="px-5 py-3">Role</th>
                                <th class="px-5 py-3">Poin</th>
                                <th class="px-5 py-3">Bergabung</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($members as $member)
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if ($member->avatar)
                                                <img src="{{ Storage::url($member->avatar) }}" alt="{{ $member->name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                                            @else
                                                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 font-display text-sm font-bold text-white">
                                                    {{ Str::of($member->name)->substr(0, 1)->upper() }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="truncate font-semibold text-slate-800">{{ $member->name }}</p>
                                                <p class="truncate text-xs text-slate-400">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $member->division->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {{ $member->role === 'super_admin' ? 'bg-primary-200 text-primary-800' : ($member->role === 'admin' ? 'bg-primary-100 text-primary-700' : 'bg-slate-100 text-slate-600') }}">
                                            {{ str_replace('_', ' ', $member->role) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $member->totalPoints() }}</td>
                                    <td class="px-5 py-3.5 text-slate-500">{{ $member->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.members.edit', $member) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-primary-50 hover:text-primary-700">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z" /></svg>
                                                Edit
                                            </a>
                                            @if ($member->id !== auth()->id())
                                                <button
                                                    type="button"
                                                    @click="deleteModal = true; deleteAction = '{{ route('admin.members.destroy', $member) }}'; deleteName = '{{ $member->name }}'"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-rose-100 hover:text-rose-700"
                                                >
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">{{ $members->links() }}</div>
        @endif

        <!-- Delete confirmation modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 grid place-items-center px-4">
            <div
                x-show="deleteModal" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                @click="deleteModal = false" class="absolute inset-0 bg-slate-900/50"
            ></div>

            <div
                x-show="deleteModal"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-playful-lg"
            >
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-rose-100">
                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <p class="mt-4 text-center font-display text-lg font-semibold text-slate-800">Hapus akun?</p>
                <p class="mt-1.5 text-center text-sm text-slate-500">
                    Akun <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen. Aksi ini tidak bisa dibatalkan.
                </p>
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
