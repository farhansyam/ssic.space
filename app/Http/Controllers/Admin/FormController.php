<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Form;
use App\Models\Kelas;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormController extends Controller
{
    public function index(Request $request): View
    {
        $forms = Form::withCount(['fields', 'submissions'])
            ->with('target')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.forms.index', compact('forms'));
    }

    public function create(): View
    {
        return view('admin.forms.create', [
            'classes' => Kelas::orderBy('title')->get(),
            'events' => Event::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('forms', 'public');
        }

        $form = Form::create($validated);

        return redirect()->route('admin.forms.edit', $form)->with('success', 'Form "'.$form->name.'" berhasil dibuat. Sekarang tambahkan field.');
    }

    public function edit(Form $form): View
    {
        $form->load('fields');

        return view('admin.forms.edit', [
            'form' => $form,
            'classes' => Kelas::orderBy('title')->get(),
            'events' => Event::orderBy('title')->get(),
            'shortLink' => ShortLink::where('target_url', route('form.show', $form))->first(),
        ]);
    }

    public function shortLink(Request $request, Form $form): RedirectResponse
    {
        $targetUrl = route('form.show', $form);
        $existing = ShortLink::where('target_url', $targetUrl)->first();

        if (! $existing) {
            $slug = Str::lower(Str::slug($form->name));
            $slug = $slug ?: Str::lower(Str::random(6));
            $base = $slug;
            $i = 1;
            while (ShortLink::where('slug', $slug)->exists()) {
                $slug = $base.'-'.(++$i);
            }

            ShortLink::create([
                'slug' => $slug,
                'target_url' => $targetUrl,
                'click_count' => 0,
                'created_by' => $request->user()->id,
            ]);
        }

        return back()->with('success', 'Short link untuk form ini siap dipakai.');
    }

    public function enableShare(Form $form): RedirectResponse
    {
        if (! $form->share_token) {
            $form->share_token = Str::random(40);
            $form->save();
        }

        return back()->with('success', 'Link berbagi respons sudah aktif.');
    }

    public function disableShare(Form $form): RedirectResponse
    {
        $form->share_token = null;
        $form->save();

        return back()->with('success', 'Link berbagi respons dinonaktifkan.');
    }

    public function update(Request $request, Form $form): RedirectResponse
    {
        $validated = $this->validated($request, $form->id);
        $validated['slug'] = $this->uniqueSlug($validated['name'], $form->id);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('forms', 'public');
        } elseif ($request->boolean('remove_banner')) {
            $validated['banner_image'] = null;
        }

        $form->update($validated);

        return redirect()->route('admin.forms.edit', $form)->with('success', 'Form "'.$form->name.'" berhasil diperbarui.');
    }

    public function destroy(Form $form): RedirectResponse
    {
        $name = $form->name;
        $form->delete();

        return redirect()->route('admin.forms.index')->with('success', 'Form "'.$name.'" berhasil dihapus.');
    }

    public function submissions(Form $form): View
    {
        $form->load('fields');
        $submissions = $form->submissions()->with('user')->latest()->paginate(20);

        return view('admin.forms.submissions', compact('form', 'submissions'));
    }

    public function exportCsv(Form $form): StreamedResponse
    {
        $form->load('fields');
        $submissions = $form->submissions()->orderBy('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$form->slug.'-submissions.csv"',
        ];

        $callback = function () use ($form, $submissions) {
            $out = fopen('php://output', 'w');
            fputcsv($out, array_merge(['Tanggal'], $form->fields->pluck('label')->all()), escape: '');

            foreach ($submissions as $submission) {
                $row = [$submission->created_at->format('Y-m-d H:i')];
                foreach ($form->fields as $field) {
                    $value = $submission->data_json[$field->id] ?? '';
                    $row[] = is_array($value) ? implode(', ', $value) : $value;
                }
                fputcsv($out, $row, escape: '');
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'banner_image' => ['nullable', 'image', 'max:2048'],
            'font_family' => ['required', 'in:'.implode(',', array_keys(Form::FONTS))],
            'theme_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'target_type' => ['nullable', 'in:kelas,event,recruitment'],
            'target_id' => ['nullable', 'required_if:target_type,kelas,event', 'integer'],
            'notify_email' => ['nullable', 'email', 'max:150'],
            'confirmation_title' => ['nullable', 'string', 'max:200'],
            'confirmation_message' => ['nullable', 'string', 'max:1000'],
            'confirmation_link_url' => ['nullable', 'url', 'max:500'],
            'confirmation_link_label' => ['nullable', 'string', 'max:100', 'required_with:confirmation_link_url'],
        ]);

        if (in_array($validated['target_type'] ?? null, ['kelas', 'event'], true)) {
            $exists = Form::where('target_type', $validated['target_type'])
                ->where('target_id', $validated['target_id'])
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists();

            if ($exists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'target_id' => 'Target ini sudah punya form pendaftaran lain.',
                ]);
            }
        } else {
            $validated['target_id'] = null;
        }

        if (empty($validated['target_type'])) {
            $validated['target_type'] = null;
        }

        return $validated;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Form::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
