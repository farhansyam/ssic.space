<x-layouts.public
    title="Blog"
    description="Cerita, tips, dan kabar terbaru seputar kegiatan sosial dari Synergy Social Impact Community."
>
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="font-display text-3xl font-bold text-slate-800 sm:text-4xl">Blog SSIC</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-500">Cerita, tips, dan kabar terbaru seputar kegiatan sosial bareng komunitas.</p>
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('blog.index') }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ !request('kategori') && !request('tag') ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('blog.index', ['kategori' => $category->slug]) }}" class="rounded-xl px-4 py-2 text-sm font-medium transition {{ request('kategori') === $category->slug ? 'bg-primary-500 text-white shadow-playful' : 'bg-white text-slate-600 border border-slate-200 hover:border-primary-300' }}">{{ $category->name }} <span class="text-xs opacity-70">({{ $category->posts_count }})</span></a>
            @endforeach
        </div>

        @if ($tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                @foreach ($tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="rounded-full px-3 py-1.5 text-xs font-medium transition {{ request('tag') === $tag->slug ? 'bg-primary-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        @if ($posts->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada artikel</p>
                <p class="mt-1 text-sm text-slate-500">Coba lagi nanti atau ubah filter.</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post) }}" class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful-lg">
                        <div class="relative h-40 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                            @if ($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: {{ $post->image_focal_x }}% {{ $post->image_focal_y }}%">
                            @endif
                            @if ($post->category)
                                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $post->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-display font-semibold text-slate-800 transition-colors group-hover:text-primary-600">{{ $post->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 text-sm text-slate-500">{{ $post->excerpt() }}</p>
                            <p class="mt-3 flex items-center gap-1.5 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                {{ $post->author->name }} &middot; {{ $post->published_at?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </div>
</x-layouts.public>
