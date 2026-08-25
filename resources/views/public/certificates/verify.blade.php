<x-layouts.public
    title="Verifikasi Sertifikat"
    description="Cek keaslian sertifikat digital SSIC."
>
    <div class="mx-auto max-w-xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($certificate)
            <div class="overflow-hidden rounded-3xl border border-emerald-100 bg-white shadow-playful-lg">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 px-6 py-8 text-center text-white">
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-white/20">
                        <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="mt-4 font-display text-xl font-bold">Sertifikat Valid</p>
                    <p class="mt-1 text-sm text-emerald-100">Sertifikat ini terverifikasi dan tercatat resmi di sistem SSIC.</p>
                </div>
                <div class="space-y-4 p-6 sm:p-8">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor Sertifikat</p>
                        <p class="mt-0.5 font-mono text-sm font-semibold text-slate-800">{{ $certificate->certificate_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Penerima</p>
                        <p class="mt-0.5 font-display text-lg font-semibold text-slate-800">{{ $certificate->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Untuk</p>
                        <p class="mt-0.5 text-sm text-slate-600">{{ $certificate->certifiable->title ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Tanggal Terbit</p>
                        <p class="mt-0.5 text-sm text-slate-600">{{ $certificate->issued_at->translatedFormat('d F Y') }}</p>
                    </div>

                    @if ($certificate->pdf_path)
                        <a href="{{ Storage::url($certificate->pdf_path) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Unduh PDF
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-playful-lg">
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 px-6 py-8 text-center text-white">
                    <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-white/20">
                        <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="mt-4 font-display text-xl font-bold">Sertifikat Tidak Ditemukan</p>
                    <p class="mt-1 text-sm text-rose-100">Nomor sertifikat <span class="font-mono">{{ $number }}</span> tidak terdaftar di sistem kami.</p>
                </div>
                <div class="p-6 text-center sm:p-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-primary-300 hover:text-primary-600">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.public>
