@php
    $focalX = $focalX ?? 50;
    $focalY = $focalY ?? 50;
@endphp
<div
    x-data="{
        localFocalX: {{ (float) $focalX }},
        localFocalY: {{ (float) $focalY }},
        onFocalClick(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            this.localFocalX = Math.round(((e.clientX - rect.left) / rect.width) * 1000) / 10;
            this.localFocalY = Math.round(((e.clientY - rect.top) / rect.height) * 1000) / 10;
        },
    }"
>
    <template x-if="preview">
        <div>
            <div class="relative mt-2.5 h-40 w-full cursor-crosshair overflow-hidden rounded-xl bg-slate-100" @click="onFocalClick($event)">
                <img :src="preview" class="pointer-events-none h-full w-full object-cover" :style="`object-position: ${localFocalX}% ${localFocalY}%`">
                <div class="pointer-events-none absolute h-6 w-6 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white bg-primary-500/70 shadow-lg" :style="`left: ${localFocalX}%; top: ${localFocalY}%`"></div>
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Klik gambar untuk atur area yang jadi fokus thumbnail.</p>
        </div>
    </template>
    <input type="hidden" name="{{ $fieldName }}_focal_x" x-model="localFocalX">
    <input type="hidden" name="{{ $fieldName }}_focal_y" x-model="localFocalY">
</div>
