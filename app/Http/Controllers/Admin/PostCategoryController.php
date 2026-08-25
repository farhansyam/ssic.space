<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostCategoryController extends Controller
{
    public function index(): View
    {
        $categories = PostCategory::withCount('posts')->orderBy('name')->paginate(12);

        return view('admin.post-categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        PostCategory::create($validated);

        return back()->with('success', 'Kategori "'.$validated['name'].'" berhasil dibuat.');
    }

    public function update(Request $request, PostCategory $postCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $postCategory->id);

        $postCategory->update($validated);

        return back()->with('success', 'Kategori "'.$postCategory->name.'" berhasil diperbarui.');
    }

    public function destroy(PostCategory $postCategory): RedirectResponse
    {
        $name = $postCategory->name;
        $postCategory->delete();

        return back()->with('success', 'Kategori "'.$name.'" berhasil dihapus.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            PostCategory::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
