<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    public function index(): View
    {
        $banners = HeroBanner::orderBy('sort_order')->get();

        return view('admin.hero-banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.hero-banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['image'] = $request->file('image')->store('hero-banners', 'public');
        $validated['sort_order'] = (HeroBanner::max('sort_order') ?? -1) + 1;

        HeroBanner::create($validated);

        return redirect()->route('admin.hero-banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(HeroBanner $heroBanner): View
    {
        return view('admin.hero-banners.edit', ['banner' => $heroBanner]);
    }

    public function update(Request $request, HeroBanner $heroBanner): RedirectResponse
    {
        $validated = $this->validated($request, requireImage: false);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero-banners', 'public');
        }

        $heroBanner->update($validated);

        return redirect()->route('admin.hero-banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(HeroBanner $heroBanner): RedirectResponse
    {
        $heroBanner->delete();

        return redirect()->route('admin.hero-banners.index')->with('success', 'Banner berhasil dihapus.');
    }

    public function toggle(HeroBanner $heroBanner): RedirectResponse
    {
        $heroBanner->update(['is_active' => ! $heroBanner->is_active]);

        return back()->with('success', 'Status banner berhasil diubah.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($validated['order'] as $index => $id) {
            HeroBanner::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function validated(Request $request, bool $requireImage = true): array
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'image' => [$requireImage ? 'required' : 'nullable', 'image', 'max:2048'],
            'image_focal_x' => ['nullable', 'numeric', 'between:0,100'],
            'image_focal_y' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
