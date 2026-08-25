<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementPopup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementPopupController extends Controller
{
    public function index(): View
    {
        $popups = AnnouncementPopup::orderByDesc('created_at')->paginate(10);

        return view('admin.popups.index', compact('popups'));
    }

    public function create(): View
    {
        return view('admin.popups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('popups', 'public');
        }

        AnnouncementPopup::create($validated);

        return redirect()->route('admin.popups.index')->with('success', 'Popup "'.$validated['title'].'" berhasil dibuat.');
    }

    public function edit(AnnouncementPopup $popup): View
    {
        return view('admin.popups.edit', compact('popup'));
    }

    public function update(Request $request, AnnouncementPopup $popup): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('popups', 'public');
        }

        $popup->update($validated);

        return redirect()->route('admin.popups.index')->with('success', 'Popup "'.$popup->title.'" berhasil diperbarui.');
    }

    public function destroy(AnnouncementPopup $popup): RedirectResponse
    {
        $title = $popup->title;
        $popup->delete();

        return redirect()->route('admin.popups.index')->with('success', 'Popup "'.$title.'" berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'show_frequency' => ['required', 'in:once_per_session,every_visit'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
