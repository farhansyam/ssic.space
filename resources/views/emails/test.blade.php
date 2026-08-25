<x-mail::message>
# Email Test Berhasil! 🎉

Ini email percobaan dari pengaturan SMTP **{{ site_setting('org_name', 'SSIC Space') }}**.

Kalau kamu nerima email ini, artinya pengaturan email di panel admin sudah benar dan siap dipakai buat kirim notifikasi otomatis (konfirmasi donasi, pendaftaran, sertifikat, dll).

<x-mail::button :url="route('home')">
Buka Situs
</x-mail::button>

Terima kasih,<br>
{{ site_setting('org_name', 'SSIC Space') }}
</x-mail::message>
