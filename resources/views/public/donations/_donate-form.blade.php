<div
    x-data="{
        isAnonymous: false,
        donorName: {{ Illuminate\Support\Js::from(old('donor_name', auth()->user()->name ?? '')) }},
        donorEmail: {{ Illuminate\Support\Js::from(old('donor_email', auth()->user()->email ?? '')) }},
        amount: {{ Illuminate\Support\Js::from(old('amount', '')) }},
        paymentMethod: {{ Illuminate\Support\Js::from(old('payment_method', 'QRIS')) }},
        submitting: false,
        preview: null,
        onFile(e) {
            const file = e.target.files[0];
            if (!file) { this.preview = null; return; }
            this.preview = URL.createObjectURL(file);
        },
        get amountFormatted() {
            const n = parseInt(this.amount || 0, 10);
            return isNaN(n) ? '' : new Intl.NumberFormat('id-ID').format(n);
        },
        get amountValid() { return !this.amount || parseInt(this.amount, 10) >= 10000 },
    }"
    class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Tampilkan sebagai</label>
            <div class="flex gap-3">
                <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-xl border px-4 py-2.5 text-sm transition" :class="!isAnonymous ? 'border-primary-400 bg-primary-50 text-primary-700' : 'border-slate-200 text-slate-500'">
                    <input type="radio" name="is_anonymous" value="0" x-model="isAnonymous" :checked="!isAnonymous" @change="isAnonymous = false" class="text-primary-500 focus:ring-primary-400">
                    Nama Saya
                </label>
                <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-xl border px-4 py-2.5 text-sm transition" :class="isAnonymous ? 'border-primary-400 bg-primary-50 text-primary-700' : 'border-slate-200 text-slate-500'">
                    <input type="radio" name="is_anonymous" value="1" x-model="isAnonymous" @change="isAnonymous = true" class="text-primary-500 focus:ring-primary-400">
                    Anonim (Hamba Allah)
                </label>
            </div>
        </div>

        <div
            x-show="!isAnonymous"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        >
            <label for="donor_name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
            <input id="donor_name" name="donor_name" type="text" x-model="donorName" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Nama kamu">
            @error('donor_name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="donor_email" class="mb-1.5 block text-sm font-medium text-slate-700">Email <span class="text-slate-400">(opsional, buat kirim konfirmasi)</span></label>
            <input id="donor_email" name="donor_email" type="email" x-model="donorEmail" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="emailmu@contoh.com">
            @error('donor_email') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="amount" class="mb-1.5 block text-sm font-medium text-slate-700">Nominal Donasi</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">Rp</span>
                <input id="amount" name="amount" type="number" min="10000" step="1000" x-model="amount" required
                    :class="!amountValid ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-200 focus:border-primary-500 focus:ring-primary-200'"
                    class="w-full rounded-xl border bg-slate-50 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:bg-white focus:ring-4" placeholder="50000">
            </div>
            <p class="mt-1.5 text-xs" :class="amountValid ? 'text-slate-400' : 'text-rose-500'" x-text="amount ? ('Rp' + amountFormatted + (amountValid ? '' : ' — minimal Rp10.000')) : 'Minimal Rp10.000'"></p>
            @error('amount') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="payment_method" class="mb-1.5 block text-sm font-medium text-slate-700">Metode Pembayaran</label>
            <select id="payment_method" name="payment_method" x-model="paymentMethod" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                <option value="QRIS">QRIS</option>
                <option value="Transfer Bank">Transfer Bank Manual</option>
                <option value="Lainnya">Lainnya</option>
            </select>
            @error('payment_method') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        @if (site_setting('qris_image'))
            <div
                x-show="paymentMethod === 'QRIS'"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                class="flex flex-col items-center gap-2 rounded-xl border border-primary-100 bg-primary-50 px-4 py-4 text-center"
            >
                <img src="{{ Storage::url(site_setting('qris_image')) }}" alt="QRIS" loading="lazy" class="h-44 w-44 rounded-lg bg-white object-contain p-2 shadow-sm">
                <p class="text-xs text-primary-700">Scan QR ini lewat aplikasi e-wallet atau m-banking kamu, lalu unggah bukti transfer di bawah.</p>
            </div>
        @endif

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Bukti Transfer <span class="text-slate-400">(opsional, maks 2MB)</span></label>
            <div class="flex items-center gap-4">
                <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-2xl bg-slate-100">
                    <template x-if="preview"><img :src="preview" class="h-full w-full object-cover" alt="Preview"></template>
                    <template x-if="!preview"><svg class="h-7 w-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 3.75h-12v16.5h12V3.75z" /></svg></template>
                </div>
                <label class="flex-1 cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-center text-sm text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                    <span>Klik untuk unggah bukti transfer</span>
                    <input type="file" name="proof_image" accept="image/png,image/jpeg" @change="onFile" class="hidden">
                </label>
            </div>
            @error('proof_image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="mb-1.5 block text-sm font-medium text-slate-700">Pesan / Doa <span class="text-slate-400">(opsional)</span></label>
            <textarea id="message" name="message" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Tinggalkan pesan atau doa..."></textarea>
        </div>

        <button type="submit" :disabled="submitting || !amount || !amountValid" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
            <span x-text="submitting ? 'Memproses...' : 'Kirim Donasi'"></span>
        </button>
    </form>
</div>
