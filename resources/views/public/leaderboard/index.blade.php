<x-layouts.public
    title="Leaderboard Volunteer"
    description="Papan peringkat member paling aktif berdasarkan poin partisipasi kegiatan, kelas, dan donasi."
>
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 px-4 py-16 text-center sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 overflow-hidden opacity-20">
            <div class="absolute -left-10 top-6 h-40 w-40 rounded-full bg-white blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-white blur-3xl"></div>
        </div>
        <div class="relative">
            <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">Leaderboard Volunteer</h1>
            <p class="mx-auto mt-3 max-w-md text-primary-100">Poin didapat dari kehadiran kelas &amp; kegiatan, serta donasi yang dikonfirmasi. Makin aktif, makin banyak badge!</p>

            @auth
                <div class="mx-auto mt-6 inline-flex items-center gap-4 rounded-2xl bg-white/15 px-6 py-3 backdrop-blur">
                    <div class="text-left">
                        <p class="text-xs text-primary-100">Poin Kamu</p>
                        <p class="font-display text-xl font-bold text-white">{{ $myPoints }}</p>
                    </div>
                    @if ($myRank)
                        <div class="h-8 w-px bg-white/25"></div>
                        <div class="text-left">
                            <p class="text-xs text-primary-100">Peringkat</p>
                            <p class="font-display text-xl font-bold text-white">#{{ $myRank }}</p>
                        </div>
                    @endif
                </div>
            @endauth
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($rankings->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <p class="font-display text-lg font-semibold text-slate-700">Belum ada peringkat</p>
                <p class="mt-1 text-sm text-slate-500">Poin akan muncul saat member mulai aktif ikut kelas, kegiatan, atau donasi.</p>
            </div>
        @else
            @php $top3 = $rankings->take(3); $rest = $rankings->slice(3); @endphp

            @if ($top3->isNotEmpty())
                <div class="mb-10 grid grid-cols-3 items-end gap-3">
                    @foreach ([1, 0, 2] as $pos)
                        @continue(! isset($top3[$pos]))
                        @php $user = $top3[$pos]; @endphp
                        <div class="flex flex-col items-center {{ $pos === 0 ? 'order-2' : ($pos === 1 ? 'order-1' : 'order-3') }}">
                            <div class="relative">
                                <div class="grid {{ $pos === 0 ? 'h-20 w-20' : 'h-16 w-16' }} place-items-center rounded-full bg-gradient-to-br {{ $pos === 0 ? 'from-sunny-300 to-sunny-500' : ($pos === 1 ? 'from-slate-300 to-slate-400' : 'from-orange-300 to-orange-500') }} font-display text-xl font-bold text-white shadow-playful-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 rounded-full bg-white px-2 py-0.5 text-xs font-bold text-slate-700 shadow">#{{ $pos + 1 }}</span>
                            </div>
                            <p class="mt-3 max-w-[6rem] truncate text-center text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs text-primary-600">{{ $user->point_logs_sum_points }} poin</p>
                            <div class="mt-2 flex gap-1">
                                @foreach ($user->userBadges->take(3) as $ub)
                                    <span class="text-base" title="{{ $ub->badge->name }}">{{ $ub->badge->icon }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($rest->isNotEmpty())
                <div class="space-y-2.5">
                    @foreach ($rest as $index => $user)
                        <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-playful">
                            <span class="w-6 text-center font-display text-sm font-bold text-slate-400">#{{ $index + 4 }}</span>
                            <div class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 font-display text-sm font-semibold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-slate-800">{{ $user->name }}</p>
                                @if ($user->userBadges->isNotEmpty())
                                    <div class="mt-0.5 flex gap-1">
                                        @foreach ($user->userBadges->take(5) as $ub)
                                            <span class="text-sm" title="{{ $ub->badge->name }}">{{ $ub->badge->icon }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="rounded-full bg-primary-50 px-3 py-1 text-sm font-semibold text-primary-700">{{ $user->point_logs_sum_points }} poin</span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-layouts.public>
