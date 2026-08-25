<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonationCampaignController extends Controller
{
    public function index(Request $request): View
    {
        $campaigns = DonationCampaign::withCount('donations')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('admin.donation-campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.donation-campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign = DonationCampaign::create(Arr::except($validated, ['meta_title', 'meta_description']));
        $this->saveSeoMeta($campaign, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? null);

        return redirect()->route('admin.donation-campaigns.index')->with('success', 'Campaign "'.$validated['title'].'" berhasil dibuat.');
    }

    public function edit(DonationCampaign $campaign): View
    {
        $campaign->load('seoMeta');

        return view('admin.donation-campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, DonationCampaign $campaign): RedirectResponse
    {
        $validated = $this->validated($request, $campaign->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update(Arr::except($validated, ['meta_title', 'meta_description']));
        $this->saveSeoMeta($campaign, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? $campaign->image);

        return redirect()->route('admin.donation-campaigns.index')->with('success', 'Campaign "'.$campaign->title.'" berhasil diperbarui.');
    }

    public function destroy(DonationCampaign $campaign): RedirectResponse
    {
        $title = $campaign->title;
        $campaign->delete();

        return redirect()->route('admin.donation-campaigns.index')->with('success', 'Campaign "'.$title.'" berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:2048'],
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
            DonationCampaign::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
