<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Event;
use App\Models\EventGallery;
use App\Models\EventRegistration;
use App\Models\Form;
use App\Services\PointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::withCount(['activeRegistrations', 'galleries'])
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('event_date')
            ->paginate(9)
            ->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        $availableForms = Form::whereNull('target_type')->orderBy('name')->get();

        return view('admin.events.create', ['event' => null, 'availableForms' => $availableForms, 'currentFormId' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create(Arr::except($validated, ['meta_title', 'meta_description', 'form_id']));
        $this->saveSeoMeta($event, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? null);
        $this->syncForm($event, $request->integer('form_id') ?: null);

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan "'.$validated['title'].'" berhasil dibuat.');
    }

    public function edit(Event $event): View
    {
        $event->load('seoMeta', 'registrationForm');

        $availableForms = Form::where(function ($query) use ($event) {
            $query->whereNull('target_type')
                ->orWhere(fn ($q) => $q->where('target_type', 'event')->where('target_id', $event->id));
        })->orderBy('name')->get();

        return view('admin.events.edit', [
            'event' => $event,
            'availableForms' => $availableForms,
            'currentFormId' => $event->registrationForm?->id,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $this->validated($request, $event->id);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update(Arr::except($validated, ['meta_title', 'meta_description', 'form_id']));
        $this->saveSeoMeta($event, $validated['meta_title'], $validated['meta_description'], $validated['image'] ?? $event->image);
        $this->syncForm($event, $request->integer('form_id') ?: null);

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan "'.$event->title.'" berhasil diperbarui.');
    }

    private function syncForm(Event $event, ?int $formId): void
    {
        Form::where('target_type', 'event')->where('target_id', $event->id)
            ->when($formId, fn ($q) => $q->where('id', '!=', $formId))
            ->update(['target_type' => null, 'target_id' => null]);

        if ($formId) {
            Form::where('id', $formId)->update(['target_type' => 'event', 'target_id' => $event->id]);
        }
    }

    public function destroy(Event $event): RedirectResponse
    {
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan "'.$title.'" berhasil dihapus.');
    }

    public function participants(Event $event): View
    {
        $registrations = $event->registrations()->with('user')->latest()->paginate(20);
        $templates = CertificateTemplate::orderBy('name')->get();
        $certifiedUserIds = Certificate::where('certifiable_type', $event->getMorphClass())
            ->where('certifiable_id', $event->id)
            ->pluck('user_id');

        return view('admin.events.participants', compact('event', 'registrations', 'templates', 'certifiedUserIds'));
    }

    public function updateParticipantStatus(Request $request, Event $event, EventRegistration $registration, PointService $points): RedirectResponse
    {
        abort_unless($registration->event_id === $event->id, 404);

        $validated = $request->validate([
            'status' => ['required', 'in:terdaftar,hadir,batal'],
        ]);

        $registration->update($validated);

        if ($validated['status'] === 'hadir' && $registration->user) {
            $points->award($registration->user, 'event_registration', $registration->id, PointService::POINTS_EVENT_HADIR);
        }

        return back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    public function gallery(Event $event): View
    {
        $event->load('galleries');

        return view('admin.events.gallery', compact('event'));
    }

    public function galleryStore(Request $request, Event $event): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($request->file('images') as $file) {
            EventGallery::create([
                'event_id' => $event->id,
                'image_path' => $file->store('events/gallery', 'public'),
                'caption' => $request->string('caption')->toString() ?: null,
            ]);
        }

        return back()->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function galleryDestroy(Event $event, EventGallery $gallery): RedirectResponse
    {
        abort_unless($gallery->event_id === $event->id, 404);
        $gallery->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'pj_name' => ['nullable', 'string', 'max:150'],
            'pj_whatsapp' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:upcoming,selesai'],
            'registration_type' => ['required', 'in:member,umum'],
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
            Event::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
