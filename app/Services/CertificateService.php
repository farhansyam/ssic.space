<?php

namespace App\Services;

use App\Mail\CertificateIssuedMail;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService
{
    public function issue(User $user, Model $certifiable, CertificateTemplate $template, string $typeLabel): Certificate
    {
        $number = $this->generateNumber();

        $detailLabel = $certifiable->title ?? '';

        $pdf = Pdf::loadView('pdfs.certificate', [
            'backgroundPath' => Storage::disk('public')->path($template->background_image),
            'layout' => $template->layout_json ?? [],
            'values' => [
                'name' => $user->name,
                'detail' => $typeLabel.': '.$detailLabel,
                'number' => $number,
                'date' => now()->translatedFormat('d F Y'),
            ],
        ])->setPaper('a4', 'landscape');

        $path = 'certificates/'.$number.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $certificate = Certificate::create([
            'certificate_number' => $number,
            'user_id' => $user->id,
            'certifiable_type' => $certifiable->getMorphClass(),
            'certifiable_id' => $certifiable->id,
            'issued_at' => now(),
            'pdf_path' => $path,
        ]);

        send_mail_safely($user->email, new CertificateIssuedMail($certificate->load('user', 'certifiable')));

        return $certificate;
    }

    private function generateNumber(): string
    {
        do {
            $number = 'SSIC-'.now()->format('Y').'-'.Str::upper(Str::random(6));
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }
}
