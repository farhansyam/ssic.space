<x-mail::message>
# Sertifikat Kamu Sudah Terbit! 🎓

Selamat, {{ $certificate->user->name }}! Sertifikat kamu untuk **{{ $certificate->certifiable->title ?? '-' }}** sudah diterbitkan oleh {{ site_setting('org_name', 'SSIC Space') }}.

<x-mail::table>
| | |
|:---|---:|
| Nomor Sertifikat | {{ $certificate->certificate_number }} |
| Tanggal Terbit | {{ $certificate->issued_at->translatedFormat('d F Y') }} |
</x-mail::table>

@if ($certificate->pdf_path)
<x-mail::button :url="Illuminate\Support\Facades\Storage::url($certificate->pdf_path)">
Unduh Sertifikat (PDF)
</x-mail::button>
@endif

Sertifikat ini juga bisa diverifikasi keasliannya kapan saja lewat link di bawah.

<x-mail::button :url="route('sertifikat.verify', $certificate->certificate_number)" color="success">
Verifikasi Sertifikat
</x-mail::button>

Selamat & sukses selalu,<br>
{{ site_setting('org_name', 'SSIC Space') }}
</x-mail::message>
