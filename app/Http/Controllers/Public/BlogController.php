<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['category', 'author', 'tags'])
            ->where('status', 'publish')
            ->when($request->filled('kategori'), function ($q) use ($request) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('kategori')));
            })
            ->when($request->filled('tag'), function ($q) use ($request) {
                $q->whereHas('tags', fn ($t) => $t->where('slug', $request->string('tag')));
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = PostCategory::withCount('posts')->orderBy('name')->get();
        $tags = PostTag::withCount('posts')->orderBy('name')->get();

        return view('public.blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'publish', 404);

        $post->load(['category', 'author', 'tags', 'seoMeta']);

        $relatedPosts = Post::where('status', 'publish')
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('category_id', $post->category_id)
                    ->orWhereHas('tags', fn ($t) => $t->whereIn('post_tags.id', $post->tags->pluck('id')));
            })
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', compact('post', 'relatedPosts'));
    }
}
