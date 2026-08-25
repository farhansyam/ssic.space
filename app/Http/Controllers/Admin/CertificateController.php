<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\ClassRegistration;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Kelas;
use App\Services\CertificateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = Certificate::with(['user', 'certifiable'])
            ->orderByDesc('issued_at')
            ->paginate(20);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function issueForClass(Request $request, Kelas $kelas, ClassRegistration $registration, CertificateService $service): RedirectResponse
    {
        abort_unless($registration->class_id === $kelas->id, 404);
        abort_unless($registration->status === 'hadir', 422, 'Peserta belum berstatus hadir.');

        $template = $this->resolveTemplate($request);

        $service->issue($registration->user, $kelas, $template, 'Kelas');

        return back()->with('success', 'Sertifikat untuk '.$registration->user->name.' berhasil diterbitkan.');
    }

    public function issueForEvent(Request $request, Event $event, EventRegistration $registration, CertificateService $service): RedirectResponse
    {
        abort_unless($registration->event_id === $event->id, 404);
        abort_unless($registration->status === 'hadir', 422, 'Peserta belum berstatus hadir.');

        $template = $this->resolveTemplate($request);

        $service->issue($registration->user, $event, $template, 'Kegiatan');

        return back()->with('success', 'Sertifikat untuk '.$registration->user->name.' berhasil diterbitkan.');
    }

    public function downloadBatch(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:certificates,id'],
        ]);

        $certificates = Certificate::with('user')
            ->whereIn('id', $validated['ids'])
            ->whereNotNull('pdf_path')
            ->get();

        abort_if($certificates->isEmpty(), 404, 'Tidak ada sertifikat dengan PDF untuk diunduh.');

        $zipName = 'sertifikat-'.now()->format('Y-m-d-His').'.zip';
        $zipPath = storage_path('app/private/tmp-'.Str::random(12).'.zip');

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $usedNames = [];
        foreach ($certificates as $certificate) {
            $fullPath = Storage::disk('public')->path($certificate->pdf_path);
            if (! is_file($fullPath)) {
                continue;
            }

            $baseName = Str::slug($certificate->user->name.'-'.$certificate->certificate_number).'.pdf';
            $entryName = $baseName;
            $i = 1;
            while (in_array($entryName, $usedNames, true)) {
                $entryName = Str::slug($certificate->user->name.'-'.$certificate->certificate_number).'-'.(++$i).'.pdf';
            }
            $usedNames[] = $entryName;

            $zip->addFile($fullPath, $entryName);
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

    private function resolveTemplate(Request $request): CertificateTemplate
    {
        $validated = $request->validate([
            'template_id' => ['required', 'exists:certificate_templates,id'],
        ]);

        return CertificateTemplate::findOrFail($validated['template_id']);
    }
}
