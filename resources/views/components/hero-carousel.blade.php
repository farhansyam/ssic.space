@props(['banners'])

<div
    x-data="{
        active: 0,
        count: {{ $banners->count() }},
        timer: null,
        next() { this.active = (this.active + 1) % this.count; },
        prev() { this.active = (this.active - 1 + this.count) % this.count; },
        goTo(i) { this.active = i; this.restart(); },
        restart() {
            clearInterval(this.timer);
            if (this.count > 1) this.timer = setInterval(() => this.next(), 6000);
        },
    }"
    x-init="restart()"
    class="relative h-[420px] w-full overflow-hidden bg-slate-900 sm:h-[480px] lg:h-[560px]"
>
    @foreach ($banners as $i => $banner)
        <div
            x-show="active === {{ $i }}"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0"
        >
            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title ?: 'SSIC' }}" class="h-full w-full object-cover" style="object-position: {{ $banner->image_focal_x }}% {{ $banner->image_focal_y }}%">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>

            @if ($banner->title || $banner->subtitle || $banner->cta_text)
                <div class="absolute inset-x-0 bottom-0 px-6 pb-12 text-center sm:pb-16">
                    @if ($banner->title)
                        <h2 class="font-display text-3xl font-bold text-white drop-shadow sm:text-4xl">{{ $banner->title }}</h2>
                    @endif
                    @if ($banner->subtitle)
                        <p class="mx-auto mt-2 max-w-xl text-white/90 drop-shadow">{{ $banner->subtitle }}</p>
                    @endif
                    @if ($banner->cta_text && $banner->cta_link)
                        <a href="{{ $banner->cta_link }}" class="mt-5 inline-block rounded-xl bg-gradient-to-r from-primary-500 to-primary-700 px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg">
                            {{ $banner->cta_text }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endforeach

    @if ($banners->count() > 1)
        <button @click="prev(); restart()" class="absolute left-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/20 text-white backdrop-blur transition hover:bg-white/30">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        </button>
        <button @click="next(); restart()" class="absolute right-4 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/20 text-white backdrop-blur transition hover:bg-white/30">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
        </button>

        <div class="absolute inset-x-0 bottom-4 flex items-center justify-center gap-2">
            @foreach ($banners as $i => $banner)
                <button
                    @click="goTo({{ $i }})"
                    class="h-2 rounded-full transition-all"
                    :class="active === {{ $i }} ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                ></button>
            @endforeach
        </div>
    @endif
</div>
