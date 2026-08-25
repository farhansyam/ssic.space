<x-mail::message>
# Pendaftaran Berhasil, {{ $recipientName }}! ✅

Kamu berhasil terdaftar di {{ $itemType }} **"{{ $itemTitle }}"**.

Pantau terus email atau akunmu di {{ site_setting('org_name', 'SSIC Space') }} buat info jadwal dan detail lebih lanjut.

@if ($detailUrl)
<x-mail::button :url="$detailUrl">
Lihat Detail
</x-mail::button>
@endif

Sampai jumpa!<br>
{{ site_setting('org_name', 'SSIC Space') }}
</x-mail::message>
