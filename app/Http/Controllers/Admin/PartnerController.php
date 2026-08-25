<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::orderByDesc('created_at')->paginate(12);

        return view('admin.partners.index', compact('partners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'logo' => ['required', 'image', 'max:2048'],
            'link' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['logo'] = $request->file('logo')->store('partners', 'public');

        Partner::create($validated);

        return back()->with('success', 'Mitra "'.$validated['name'].'" berhasil ditambahkan.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $name = $partner->name;
        $partner->delete();

        return back()->with('success', 'Mitra "'.$name.'" berhasil dihapus.');
    }
}
