<div
    x-data="{
        title: {{ Illuminate\Support\Js::from(old('title', $post->title ?? '')) }},
        submitting: false,
        showSeo: false,
        preview: {{ Illuminate\Support\Js::from($post?->featured_image ? Illuminate\Support\Facades\Storage::url($post->featured_image) : null) }},
        onFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.preview = URL.createObjectURL(file);
        },
        get slugPreview() {
            return this.title.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') || 'slug-otomatis';
        },
    }"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" @submit="submitting = true" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($method === 'PUT') @method('PUT') @endif

        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label for="title" class="mb-1.5 block text-sm font-medium text-slate-700">Judul Artikel</label>
                <input id="title" name="title" type="text" x-model="title" required maxlength="200" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-base font-medium outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Judul artikel yang menarik...">
                <p class="mt-1.5 text-xs text-slate-400">Slug: <span class="font-mono text-slate-500" x-text="slugPreview"></span></p>
                @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Konten</label>
                <x-rich-text-editor name="content" :value="old('content', $post->content ?? '')" />
                @error('content') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
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
                        <input id="meta_title" name="meta_title" type="text" maxlength="255" value="{{ old('meta_title', $post?->seoMeta?->meta_title ?? '') }}" :placeholder="title || 'Otomatis pakai judul artikel'" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    </div>
                    <div>
                        <label for="meta_description" class="mb-1.5 block text-sm font-medium text-slate-700">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2" maxlength="500" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200" placeholder="Ringkasan singkat untuk hasil pencarian & share...">{{ old('meta_description', $post?->seoMeta?->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label for="status" class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>Draft</option>
                    <option value="publish" @selected(old('status', $post->status ?? 'draft') === 'publish')>Publish</option>
                </select>
                @error('status') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror

                <button type="submit" :disabled="submitting || !title" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Artikel'"></span>
                </button>
                <a href="{{ route('admin.posts.index') }}" class="mt-2 block rounded-xl px-6 py-2.5 text-center text-sm font-semibold text-slate-500 transition hover:bg-slate-100">Batal</a>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                <select id="category_id" name="category_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                    <option value="">Tanpa kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @if ($categories->isEmpty())
                    <p class="mt-1.5 text-xs text-slate-400">Belum ada kategori. <a href="{{ route('admin.post-categories.index') }}" class="text-primary-600 underline">Buat kategori</a></p>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label class="mb-2 block text-sm font-medium text-slate-700">Tag</label>
                @if ($tags->isEmpty())
                    <p class="text-xs text-slate-400">Belum ada tag. <a href="{{ route('admin.post-tags.index') }}" class="text-primary-600 underline">Buat tag</a></p>
                @else
                    @php $selectedTags = old('tags', $post?->tags->pluck('id')->all() ?? []); @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tags as $tag)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden" @checked(in_array($tag->id, $selectedTags))>
                                <span class="inline-block rounded-full border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-500 transition peer-checked:border-primary-400 peer-checked:bg-primary-50 peer-checked:text-primary-700">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Featured Image <span class="text-slate-400">(maks 2MB)</span></label>
                <div class="grid h-32 w-full place-items-center overflow-hidden rounded-xl bg-slate-100">
                    <template x-if="preview"><img :src="preview" class="h-full w-full object-cover" alt="Preview"></template>
                    <template x-if="!preview"><svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 3.75h-12v16.5h12V3.75z" /></svg></template>
                </div>
                <label class="mt-3 block cursor-pointer rounded-xl border-2 border-dashed border-slate-200 px-4 py-2.5 text-center text-xs font-medium text-slate-500 transition hover:border-primary-400 hover:bg-primary-50 hover:text-primary-600">
                    <span>Klik untuk pilih gambar</span>
                    <input type="file" name="featured_image" accept="image/png,image/jpeg" @change="onFile" class="hidden">
                </label>
                @error('featured_image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                @include('admin._image-focal-picker', ['fieldName' => 'image', 'focalX' => $post?->image_focal_x ?? 50, 'focalY' => $post?->image_focal_y ?? 50])
            </div>
        </div>
    </form>
</div>
