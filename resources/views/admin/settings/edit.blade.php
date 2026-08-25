<x-layouts.admin title="Pengaturan Situs">
    <div class="mb-6">
        <h2 class="font-display text-2xl font-semibold text-slate-800">Pengaturan Situs</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola identitas, branding, dan kontak SSIC — tidak perlu ubah kode.</p>
    </div>

    <div
        x-data="{
            submitting: false,
            themeColor: {{ Illuminate\Support\Js::from(site_setting('theme_color', '#2474D2')) }},
            logoPreview: {{ Illuminate\Support\Js::from(site_setting('logo') ? Illuminate\Support\Facades\Storage::url(site_setting('logo')) : null) }},
            faviconPreview: {{ Illuminate\Support\Js::from(site_setting('favicon') ? Illuminate\Support\Facades\Storage::url(site_setting('favicon')) : null) }},
            heroPreview: {{ Illuminate\Support\Js::from(site_setting('hero_image') ? Illuminate\Support\Facades\Storage::url(site_setting('hero_image')) : null) }},
            qrisPreview: {{ Illuminate\Support\Js::from(site_setting('qris_image') ? Illuminate\Support\Facades\Storage::url(site_setting('qris_image')) : null) }},
            onFile(field, e) {
                const file = e.target.files[0];
                if (!file) return;
                this[field] = URL.createObjectURL(file);
            },
        }"
        class="max-w-3xl space-y-6"
    >
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Identitas Organisasi</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="org_name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Organisasi</label>
                        <input id="org_name" name="org_name" type="text" required maxlength="150" value="{{ old('org_name', site_setting('org_name', 'SSIC')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        @error('org_name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="org_tagline" class="mb-1.5 block text-sm font-medium text-slate-700">Tagline</label>
                        <input id="org_tagline" name="org_tagline" type="text" maxlength="255" value="{{ old('org_tagline', site_setting('org_tagline', 'Synergy Social Impact Community')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label for="org_description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                        <textarea id="org_description" name="org_description" rows="3" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">{{ old('org_description', site_setting('org_description')) }}</textarea>
                    </div>
                    <div>
                        <label for="org_hashtag_tagline" class="mb-1.5 block text-sm font-medium text-slate-700">Hashtag Tagline</label>
                        <input id="org_hashtag_tagline" name="org_hashtag_tagline" type="text" maxlength="100" value="{{ old('org_hashtag_tagline', site_setting('org_hashtag_tagline', '#Reform #Transform #Perform')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        <p class="mt-1.5 text-xs text-slate-400">Muncul di beranda, footer, dan CTA — sesuai brand kit.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Branding</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Logo</label>
                        <div class="grid h-20 w-full place-items-center overflow-hidden rounded-xl bg-slate-100">
                            <template x-if="logoPreview"><img :src="logoPreview" class="h-full w-full object-contain p-2"></template>
                            <template x-if="!logoPreview"><span class="font-display text-xs text-slate-400">Belum ada</span></template>
                        </div>
                        <label class="mt-2 block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-3 py-2 text-center text-xs text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                            Ganti logo
                            <input type="file" name="logo" accept="image/png,image/jpeg" @change="onFile('logoPreview', $event)" class="hidden">
                        </label>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Favicon</label>
                        <div class="grid h-20 w-full place-items-center overflow-hidden rounded-xl bg-slate-100">
                            <template x-if="faviconPreview"><img :src="faviconPreview" class="h-full w-full object-contain p-2"></template>
                            <template x-if="!faviconPreview"><span class="font-display text-xs text-slate-400">Belum ada</span></template>
                        </div>
                        <label class="mt-2 block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-3 py-2 text-center text-xs text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                            Ganti favicon
                            <input type="file" name="favicon" accept="image/png,image/jpeg" @change="onFile('faviconPreview', $event)" class="hidden">
                        </label>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Foto Hero</label>
                        <div class="grid h-20 w-full place-items-center overflow-hidden rounded-xl bg-slate-100">
                            <template x-if="heroPreview"><img :src="heroPreview" class="h-full w-full object-cover"></template>
                            <template x-if="!heroPreview"><span class="font-display text-xs text-slate-400">Belum ada</span></template>
                        </div>
                        <label class="mt-2 block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-3 py-2 text-center text-xs text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                            Ganti foto hero
                            <input type="file" name="hero_image" accept="image/png,image/jpeg" @change="onFile('heroPreview', $event)" class="hidden">
                        </label>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="theme_color" class="mb-1.5 block text-sm font-medium text-slate-700">Warna Tema Utama</label>
                    <div class="flex items-center gap-3">
                        <input type="color" x-model="themeColor" class="h-11 w-14 cursor-pointer rounded-lg border border-slate-200 bg-white p-1">
                        <input id="theme_color" name="theme_color" type="text" x-model="themeColor" maxlength="7" class="w-32 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-mono outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                        <span class="text-xs text-slate-400">Dipakai untuk tombol &amp; aksen utama di halaman publik</span>
                    </div>
                    @error('theme_color') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Pembayaran</h3>
                <p class="mt-1 text-sm text-slate-500">Kode QR ini akan ditampilkan ke donatur saat memilih metode QRIS.</p>
                <div class="mt-4 flex items-center gap-4">
                    <div class="grid h-28 w-28 shrink-0 place-items-center overflow-hidden rounded-xl bg-slate-100">
                        <template x-if="qrisPreview"><img :src="qrisPreview" class="h-full w-full object-contain p-2"></template>
                        <template x-if="!qrisPreview"><span class="px-2 text-center font-display text-xs text-slate-400">Belum ada QR</span></template>
                    </div>
                    <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                        Unggah gambar QRIS
                        <input type="file" name="qris_image" accept="image/png,image/jpeg" @change="onFile('qrisPreview', $event)" class="hidden">
                    </label>
                </div>
                @error('qris_image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Kontak &amp; Sosial Media</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="contact_email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                        <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', site_setting('contact_email')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="halo@ssic.id">
                        @error('contact_email') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="mb-1.5 block text-sm font-medium text-slate-700">No. Telepon / WA</label>
                        <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', site_setting('contact_phone')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="0812xxxxxxx">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="contact_address" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label>
                        <input id="contact_address" name="contact_address" type="text" value="{{ old('contact_address', site_setting('contact_address')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label for="social_instagram" class="mb-1.5 block text-sm font-medium text-slate-700">Instagram</label>
                        <input id="social_instagram" name="social_instagram" type="text" value="{{ old('social_instagram', site_setting('social_instagram')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="https://instagram.com/ssic.impact">
                    </div>
                    <div>
                        <label for="social_facebook" class="mb-1.5 block text-sm font-medium text-slate-700">Facebook</label>
                        <input id="social_facebook" name="social_facebook" type="text" value="{{ old('social_facebook', site_setting('social_facebook')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label for="social_whatsapp" class="mb-1.5 block text-sm font-medium text-slate-700">WhatsApp (link)</label>
                        <input id="social_whatsapp" name="social_whatsapp" type="text" value="{{ old('social_whatsapp', site_setting('social_whatsapp')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="https://wa.me/62812xxxxxxx">
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 class="font-display font-semibold text-slate-800">Notifikasi Email (SMTP)</h3>
                <p class="mt-1 text-sm text-slate-500">Dipakai buat kirim email otomatis — konfirmasi donasi, pendaftaran, sertifikat terbit, dll. Semua diatur di sini, tidak perlu edit file server.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="mail_host" class="mb-1.5 block text-sm font-medium text-slate-700">SMTP Host</label>
                        <input id="mail_host" name="mail_host" type="text" value="{{ old('mail_host', site_setting('mail_host')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="smtp.gmail.com">
                        @error('mail_host') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="mail_port" class="mb-1.5 block text-sm font-medium text-slate-700">Port</label>
                            <input id="mail_port" name="mail_port" type="number" value="{{ old('mail_port', site_setting('mail_port', '587')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                            @error('mail_port') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="mail_encryption" class="mb-1.5 block text-sm font-medium text-slate-700">Enkripsi</label>
                            <select id="mail_encryption" name="mail_encryption" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                @foreach (['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'Tanpa'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('mail_encryption', site_setting('mail_encryption', 'tls')) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="mail_username" class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
                        <input id="mail_username" name="mail_username" type="text" value="{{ old('mail_username', site_setting('mail_username')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="notifikasi@ssic.id" autocomplete="off">
                    </div>
                    <div>
                        <label for="mail_password" class="mb-1.5 block text-sm font-medium text-slate-700">Password / App Password</label>
                        <input id="mail_password" name="mail_password" type="password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="{{ site_setting('mail_password') ? '••••••••• (biarkan kosong jika tidak diganti)' : '' }}" autocomplete="new-password">
                    </div>
                    <div>
                        <label for="mail_from_address" class="mb-1.5 block text-sm font-medium text-slate-700">Kirim dari (email)</label>
                        <input id="mail_from_address" name="mail_from_address" type="email" value="{{ old('mail_from_address', site_setting('mail_from_address')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="notifikasi@ssic.id">
                        @error('mail_from_address') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="mail_from_name" class="mb-1.5 block text-sm font-medium text-slate-700">Kirim dari (nama)</label>
                        <input id="mail_from_name" name="mail_from_name" type="text" value="{{ old('mail_from_name', site_setting('mail_from_name')) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="SSIC">
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="submitting" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Pengaturan'"></span>
            </button>
        </form>

        <form method="POST" action="{{ route('admin.settings.test-mail') }}" x-data="{ sending: false }" @submit="sending = true">
            @csrf
            <button type="submit" :disabled="sending" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600 disabled:cursor-not-allowed disabled:opacity-60">
                <svg x-show="sending" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span x-text="sending ? 'Mengirim...' : 'Kirim Email Test ke ' + '{{ auth()->user()->email }}'"></span>
            </button>
        </form>
    </div>
</x-layouts.admin>
