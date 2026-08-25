<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['category', 'author'])
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = PostCategory::orderBy('name')->get();
        $tags = PostTag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('featured_image')) {
            $validated['image_upload'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post = $this->save(new Post, $validated, $request);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel "'.$post->title.'" berhasil disimpan.');
    }

    public function edit(Post $post): View
    {
        $post->load('tags', 'seoMeta');
        $categories = PostCategory::orderBy('name')->get();
        $tags = PostTag::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validated($request, $post->id);

        if ($request->hasFile('featured_image')) {
            $validated['image_upload'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post = $this->save($post, $validated, $request);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel "'.$post->title.'" berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $title = $post->title;
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel "'.$title.'" berhasil dihapus.');
    }

    private function save(Post $post, array $validated, Request $request): Post
    {
        $data = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?: null,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'publish' ? ($post->published_at ?? now()) : null,
            'author_id' => $post->author_id ?? $request->user()->id,
        ];

        if (isset($validated['image_focal_x'])) {
            $data['image_focal_x'] = $validated['image_focal_x'];
        }
        if (isset($validated['image_focal_y'])) {
            $data['image_focal_y'] = $validated['image_focal_y'];
        }

        if (isset($validated['image_upload'])) {
            $data['featured_image'] = $validated['image_upload'];
        }

        $post->fill($data);
        $post->save();

        $post->tags()->sync($validated['tags'] ?? []);

        $this->saveSeoMeta($post, $validated['meta_title'], $validated['meta_description'], $validated['image_upload'] ?? $post->featured_image);

        return $post;
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:post_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:post_tags,id'],
            'status' => ['required', 'in:draft,publish'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'image_focal_x' => ['nullable', 'numeric', 'between:0,100'],
            'image_focal_y' => ['nullable', 'numeric', 'between:0,100'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title'], $ignoreId);

        return $validated;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Post::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
