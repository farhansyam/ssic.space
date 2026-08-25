<x-layouts.admin title="Buat Template Sertifikat">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.certificate-templates.index') }}" class="grid h-9 w-9 place-items-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        </a>
        <div>
            <h2 class="font-display text-2xl font-semibold text-slate-800">Buat Template Sertifikat</h2>
            <p class="mt-0.5 text-sm text-slate-500">Upload background dan atur posisi teks yang otomatis terisi.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.certificate-templates.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.certificate-templates._form')
    </form>
</x-layouts.admin>
