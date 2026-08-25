<x-layouts.public
    :title="$post->seoMeta?->meta_title ?? $post->title"
    :description="$post->seoMeta?->meta_description ?? $post->excerpt()"
    :image="$post->seoMeta?->og_image ?? $post->featured_image"
    type="article"
>
    <script type="application/ld+json">
        {!! json_encode([
            '@@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->seoMeta?->meta_description ?? $post->excerpt(),
            'image' => $post->featured_image ? asset(\Illuminate\Support\Facades\Storage::url($post->featured_image)) : null,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SSIC',
            ],
            'mainEntityOfPage' => route('blog.show', $post),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke blog
        </a>

        <article class="mt-4">
            @if ($post->category)
                <a href="{{ route('blog.index', ['kategori' => $post->category->slug]) }}" class="inline-block rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700">{{ $post->category->name }}</a>
            @endif

            <h1 class="mt-3 font-display text-3xl font-bold text-slate-800 sm:text-4xl">{{ $post->title }}</h1>

            <div class="mt-4 flex items-center gap-3 text-sm text-slate-400">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-cokelat-500 to-primary-700 font-display text-xs font-semibold text-white">{{ Str::of($post->author->name)->substr(0, 1)->upper() }}</span>
                <span>{{ $post->author->name }}</span>
                <span>&middot;</span>
                <span>{{ $post->published_at?->translatedFormat('d F Y') }}</span>
            </div>

            @if ($post->featured_image)
                <div class="mt-6 overflow-hidden rounded-3xl bg-slate-100">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full object-cover">
                </div>
            @endif

            <div class="prose prose-slate mt-8 max-w-none [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-semibold [&_h2]:mt-6 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:mt-5 [&_blockquote]:border-l-4 [&_blockquote]:border-primary-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-slate-500 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6 [&_a]:text-primary-600 [&_a]:underline [&_p]:mt-3 [&_p]:leading-relaxed [&_p]:text-slate-600">
                {!! $post->content !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2 border-t border-slate-100 pt-6">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-500 transition hover:bg-primary-100 hover:text-primary-700">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
        </article>

        @if ($relatedPosts->isNotEmpty())
            <div class="mt-12 border-t border-slate-100 pt-8">
                <h2 class="font-display text-lg font-semibold text-slate-800">Artikel Terkait</h2>
                <div class="mt-4 grid gap-5 sm:grid-cols-3">
                    @foreach ($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related) }}" class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-playful">
                            <div class="relative h-24 w-full overflow-hidden bg-gradient-to-br from-primary-400 to-primary-600">
                                @if ($related->featured_image)
                                    <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="line-clamp-2 text-sm font-medium text-slate-700 transition-colors group-hover:text-primary-600">{{ $related->title }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.public>
