<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificateTemplateController extends Controller
{
    public function index(): View
    {
        $templates = CertificateTemplate::orderByDesc('created_at')->get();

        return view('admin.certificate-templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.certificate-templates.create', ['template' => new CertificateTemplate()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'background_image' => ['required', 'image', 'max:4096'],
            'layout_json' => ['required', 'json'],
        ]);

        CertificateTemplate::create([
            'name' => $validated['name'],
            'background_image' => $request->file('background_image')->store('certificate-templates', 'public'),
            'layout_json' => json_decode($validated['layout_json'], true),
        ]);

        return redirect()->route('admin.certificate-templates.index')->with('success', 'Template sertifikat "'.$validated['name'].'" berhasil dibuat.');
    }

    public function edit(CertificateTemplate $certificateTemplate): View
    {
        return view('admin.certificate-templates.edit', ['template' => $certificateTemplate]);
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'background_image' => ['nullable', 'image', 'max:4096'],
            'layout_json' => ['required', 'json'],
        ]);

        $data = [
            'name' => $validated['name'],
            'layout_json' => json_decode($validated['layout_json'], true),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('certificate-templates', 'public');
        }

        $certificateTemplate->update($data);

        return redirect()->route('admin.certificate-templates.index')->with('success', 'Template sertifikat "'.$certificateTemplate->name.'" berhasil diperbarui.');
    }

    public function preview(Request $request): Response
    {
        $validated = $request->validate([
            'layout_json' => ['required', 'json'],
            'background_image' => ['nullable', 'image', 'max:4096'],
            'template_id' => ['nullable', 'exists:certificate_templates,id'],
        ]);

        if ($request->hasFile('background_image')) {
            $backgroundPath = $request->file('background_image')->getRealPath();
        } elseif (! empty($validated['template_id'])) {
            $backgroundPath = Storage::disk('public')->path(
                CertificateTemplate::findOrFail($validated['template_id'])->background_image
            );
        } else {
            abort(422, 'Unggah gambar latar terlebih dahulu untuk melihat preview.');
        }

        $pdf = Pdf::loadView('pdfs.certificate', [
            'backgroundPath' => $backgroundPath,
            'layout' => json_decode($validated['layout_json'], true),
            'values' => [
                'name' => 'Nama Peserta',
                'detail' => 'Kelas: Menulis Kreatif (Contoh)',
                'number' => 'SSIC-'.now()->format('Y').'-ABC123',
                'date' => now()->translatedFormat('d F Y'),
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('preview-sertifikat.pdf');
    }

    public function destroy(CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $name = $certificateTemplate->name;
        $certificateTemplate->delete();

        return redirect()->route('admin.certificate-templates.index')->with('success', 'Template sertifikat "'.$name.'" berhasil dihapus.');
    }
}
