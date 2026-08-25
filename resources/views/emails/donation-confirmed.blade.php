<x-mail::message>
# Terima Kasih, {{ $donation->donor_name }}! 💙

Donasi kamu sudah **terkonfirmasi** oleh admin {{ site_setting('org_name', 'SSIC Space') }}.

<x-mail::table>
| | |
|:---|---:|
| Nominal | Rp{{ number_format($donation->amount, 0, ',', '.') }} |
| Campaign | {{ $donation->campaign->title ?? 'Donasi Umum' }} |
| Tanggal | {{ $donation->updated_at->translatedFormat('d F Y') }} |
</x-mail::table>

Bantuan kamu bakal disalurkan dan dilaporkan transparan lewat halaman campaign. Terima kasih sudah jadi bagian dari gerakan ini.

<x-mail::button :url="route('donasi.index')">
Lihat Campaign Lainnya
</x-mail::button>

Salam hangat,<br>
{{ site_setting('org_name', 'SSIC Space') }}
</x-mail::message>
