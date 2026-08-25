<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\ClassRegistration;
use App\Models\Kelas;
use App\Services\PointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $classes = Kelas::withCount('activeRegistrations')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('admin.kelas.index', compact('classes'));
    }

    public function create(): View
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('kelas', 'public');
        }

        $kelas = Kelas::create(Arr::except($validated, ['meta_title', 'meta_description']));
        $this->saveSeoMeta($kelas, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? null);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas "'.$validated['title'].'" berhasil dibuat.');
    }

    public function edit(Kelas $kelas): View
    {
        $kelas->load('seoMeta');

        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $this->validated($request, $kelas->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('kelas', 'public');
        }

        $kelas->update(Arr::except($validated, ['meta_title', 'meta_description']));
        $this->saveSeoMeta($kelas, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? $kelas->image);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas "'.$kelas->title.'" berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $title = $kelas->title;
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas "'.$title.'" berhasil dihapus.');
    }

    public function participants(Kelas $kelas): View
    {
        $registrations = $kelas->registrations()->with('user')->latest()->paginate(20);
        $templates = CertificateTemplate::orderBy('name')->get();
        $certifiedUserIds = Certificate::where('certifiable_type', $kelas->getMorphClass())
            ->where('certifiable_id', $kelas->id)
            ->pluck('user_id');

        return view('admin.kelas.participants', compact('kelas', 'registrations', 'templates', 'certifiedUserIds'));
    }

    public function updateParticipantStatus(Request $request, Kelas $kelas, ClassRegistration $registration, PointService $points): RedirectResponse
    {
        abort_unless($registration->class_id === $kelas->id, 404);

        $validated = $request->validate([
            'status' => ['required', 'in:terdaftar,hadir,batal'],
        ]);

        $registration->update($validated);

        if ($validated['status'] === 'hadir') {
            $points->award($registration->user, 'class_registration', $registration->id, PointService::POINTS_CLASS_HADIR);
        }

        return back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'in:gratis,berbayar'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'capacity' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'pj_name' => ['nullable', 'string', 'max:150'],
            'pj_whatsapp' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:draft,dibuka,penuh,selesai'],
            'image' => ['nullable', 'image', 'max:2048'],
            'image_focal_x' => ['nullable', 'numeric', 'between:0,100'],
            'image_focal_y' => ['nullable', 'numeric', 'between:0,100'],
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
            Kelas::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
