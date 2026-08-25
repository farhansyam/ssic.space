<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostTagController extends Controller
{
    public function index(): View
    {
        $tags = PostTag::withCount('posts')->orderBy('name')->paginate(20);

        return view('admin.post-tags.index', compact('tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        PostTag::create($validated);

        return back()->with('success', 'Tag "'.$validated['name'].'" berhasil dibuat.');
    }

    public function update(Request $request, PostTag $postTag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $postTag->id);

        $postTag->update($validated);

        return back()->with('success', 'Tag "'.$postTag->name.'" berhasil diperbarui.');
    }

    public function destroy(PostTag $postTag): RedirectResponse
    {
        $name = $postTag->name;
        $postTag->delete();

        return back()->with('success', 'Tag "'.$name.'" berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            PostTag::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
