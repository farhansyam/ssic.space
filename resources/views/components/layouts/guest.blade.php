<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $orgName = site_setting('org_name', 'SSIC Space'); $orgTagline = site_setting('org_tagline', 'Synergy Social Impact Community'); @endphp
    <title>{{ $title ?? $orgName }} · {{ $orgTagline }}</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 antialiased" data-pixel-transition="true">
    <x-flash-messages />

    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <!-- Playful background blobs -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-primary-300/40 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-16 h-96 w-96 rounded-full bg-cokelat-300/30 blur-3xl"></div>
            <div class="absolute right-1/3 top-1/4 h-48 w-48 rounded-full bg-primary-200/40 blur-3xl"></div>
        </div>

        <div class="relative grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-playful-lg lg:grid-cols-2">
            <!-- Brand side -->
            <div class="relative hidden flex-col justify-between bg-gradient-to-br from-primary-500 via-primary-600 to-primary-800 p-10 text-white lg:flex">
                <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 22px 22px;"></div>

                <a href="{{ route('home') }}" class="relative flex items-center gap-3">
                    @php $orgLogo = site_setting('logo'); @endphp
                    @if ($orgLogo)
                        <img src="{{ Illuminate\Support\Facades\Storage::url($orgLogo) }}" alt="{{ $orgName }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-white/30">
                    @else
                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-white/20 font-display text-lg font-bold backdrop-blur">{{ Str::of($orgName)->substr(0, 1)->upper() }}</div>
                    @endif
                    <span class="font-display text-xl font-semibold">{{ $orgName }}</span>
                </a>

                <div class="relative">
                    <h2 class="font-display text-3xl font-semibold leading-tight">Bareng-bareng bikin dampak sosial yang nyata.</h2>
                    <p class="mt-3 text-white/85">Gabung jadi bagian dari komunitas relawan, ikut kelas, kegiatan, dan gerakan donasi bersama {{ $orgName }}.</p>
                </div>

                <p class="relative text-sm text-white/70">&copy; {{ date('Y') }} {{ $orgTagline }}</p>
            </div>

            <!-- Form side -->
            <div class="p-8 sm:p-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
