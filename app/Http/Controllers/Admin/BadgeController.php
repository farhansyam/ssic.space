<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(): View
    {
        $badges = Badge::withCount('userBadges')->orderBy('name')->get();

        return view('admin.badges.index', compact('badges'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Badge::create($validated);

        return back()->with('success', 'Badge "'.$validated['name'].'" berhasil ditambahkan.');
    }

    public function update(Request $request, Badge $badge): RedirectResponse
    {
        $validated = $this->validated($request);

        $badge->update($validated);

        return back()->with('success', 'Badge "'.$badge->name.'" berhasil diperbarui.');
    }

    public function destroy(Badge $badge): RedirectResponse
    {
        $name = $badge->name;
        $badge->delete();

        return back()->with('success', 'Badge "'.$name.'" berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:10'],
            'points_threshold' => ['required', 'integer', 'min:1'],
        ]);

        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?: '🏅',
            'criteria_json' => [
                'type' => 'points_threshold',
                'value' => (int) $validated['points_threshold'],
            ],
        ];
    }
}
