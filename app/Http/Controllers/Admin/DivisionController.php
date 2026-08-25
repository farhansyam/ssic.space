<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function index(Request $request): View
    {
        $divisions = Division::withCount('users')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('admin.divisions.index', compact('divisions'));
    }

    public function create(): View
    {
        return view('admin.divisions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('divisions', 'public');
        }

        Division::create($validated);

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi "'.$validated['name'].'" berhasil dibuat.');
    }

    public function edit(Division $division): View
    {
        return view('admin.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division): RedirectResponse
    {
        $validated = $this->validated($request, $division->id);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('divisions', 'public');
        }

        $division->update($validated);

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi "'.$division->name.'" berhasil diperbarui.');
    }

    public function destroy(Division $division): RedirectResponse
    {
        $name = $division->name;
        $division->delete();

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi "'.$name.'" berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $ignoreId);

        return $validated;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Division::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
