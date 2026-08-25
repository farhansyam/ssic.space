@php
    $popup = \App\Models\AnnouncementPopup::currentlyActive()->latest()->first();
@endphp

@if ($popup)
    <div
        x-data="{
            open: false,
            key: 'ssic_popup_{{ $popup->id }}',
            init() {
                @if ($popup->show_frequency === 'once_per_session')
                    if (sessionStorage.getItem(this.key)) return;
                @endif
                setTimeout(() => { this.open = true; sessionStorage.setItem(this.key, '1'); }, 600);
            },
        }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[60] grid place-items-center px-4"
    >
        <div
            x-show="open"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="open = false"
            class="absolute inset-0 bg-slate-900/60"
        ></div>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-playful-lg"
        >
            <button @click="open = false" class="absolute right-3 top-3 z-10 grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-500 shadow transition hover:bg-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            @if ($popup->image)
                <img src="{{ Storage::url($popup->image) }}" alt="{{ $popup->title }}" class="h-48 w-full object-cover">
            @else
                <div class="h-3 w-full bg-gradient-to-r from-primary-400 to-primary-600"></div>
            @endif

            <div class="p-6 text-center">
                <h3 class="font-display text-xl font-bold text-slate-800">{{ $popup->title }}</h3>
                @if ($popup->content)
                    <p class="mt-2 text-sm text-slate-500">{{ $popup->content }}</p>
                @endif

                <div class="mt-5 flex items-center justify-center gap-3">
                    @if ($popup->cta_text && $popup->cta_link)
                        <a href="{{ $popup->cta_link }}" class="rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            {{ $popup->cta_text }}
                        </a>
                    @endif
                    <button @click="open = false" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
