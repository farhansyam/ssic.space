<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShortLinkController extends Controller
{
    public function index(Request $request): View
    {
        $links = ShortLink::when($request->filled('q'), fn ($q) => $q->where('slug', 'like', '%'.$request->string('q').'%')->orWhere('target_url', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.short-links.index', compact('links'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:short_links,slug'],
            'target_url' => ['required', 'url', 'max:500'],
        ]);

        $slug = $validated['slug'] ?: $this->randomSlug();

        ShortLink::create([
            'slug' => $slug,
            'target_url' => $validated['target_url'],
            'click_count' => 0,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Short link "'.$slug.'" berhasil dibuat.');
    }

    public function update(Request $request, ShortLink $shortLink): RedirectResponse
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:short_links,slug,'.$shortLink->id],
            'target_url' => ['required', 'url', 'max:500'],
        ]);

        $shortLink->update($validated);

        return back()->with('success', 'Short link "'.$shortLink->slug.'" berhasil diperbarui.');
    }

    public function destroy(ShortLink $shortLink): RedirectResponse
    {
        $slug = $shortLink->slug;
        $shortLink->delete();

        return back()->with('success', 'Short link "'.$slug.'" berhasil dihapus.');
    }

    private function randomSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(6));
        } while (ShortLink::where('slug', $slug)->exists());

        return $slug;
    }
}
