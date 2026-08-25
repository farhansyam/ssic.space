<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstagramPostController extends Controller
{
    public function index(): View
    {
        $posts = InstagramPost::orderByDesc('posted_at')->paginate(12);

        return view('admin.instagram-posts.index', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media_url' => ['required', 'image', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:500'],
            'permalink' => ['nullable', 'url', 'max:500'],
            'posted_at' => ['nullable', 'date'],
        ]);

        InstagramPost::create([
            'media_url' => $request->file('media_url')->store('instagram', 'public'),
            'caption' => $validated['caption'] ?? null,
            'permalink' => $validated['permalink'] ?? null,
            'posted_at' => $validated['posted_at'] ?? now(),
            'synced_at' => now(),
        ]);

        return back()->with('success', 'Post berhasil ditambahkan ke galeri.');
    }

    public function destroy(InstagramPost $instagramPost): RedirectResponse
    {
        $instagramPost->delete();

        return back()->with('success', 'Post berhasil dihapus.');
    }
}
