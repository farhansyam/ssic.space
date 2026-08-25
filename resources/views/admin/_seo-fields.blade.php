@php $seo = $seoable?->seoMeta; @endphp

<div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" x-data="{ showSeo: {{ old('meta_title') || old('meta_description') ? 'true' : 'false' }} }">
    <button type="button" @click="showSeo = !showSeo" class="flex w-full items-center justify-between text-left">
        <span class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="h-4.5 w-4.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            SEO &amp; Meta (opsional)
        </span>
        <svg class="h-4 w-4 text-slate-400 transition-transform" :class="showSeo && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
    </button>

    <div
        x-show="showSeo" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="mt-4 space-y-4"
    >
        <div>
            <label for="meta_title" class="mb-1.5 block text-sm font-medium text-slate-700">Meta Title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', $seo->meta_title ?? '') }}" placeholder="Otomatis pakai judul di atas jika kosong" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
        </div>
        <div>
            <label for="meta_description" class="mb-1.5 block text-sm font-medium text-slate-700">Meta Description</label>
            <textarea id="meta_description" name="meta_description" rows="2" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Ringkasan singkat untuk hasil pencarian & share...">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
        </div>
    </div>
</div>
