@php
    $fontStyles = [
        'sans' => "font-family: var(--font-sans);",
        'playful' => "font-family: var(--font-display);",
        'serif' => "font-family: Georgia, 'Times New Roman', serif;",
        'mono' => "font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, 'Liberation Mono', monospace;",
    ];
    $fontStyle = $fontStyles[$form->font_family] ?? $fontStyles['sans'];
    $submitted = session('form_submitted') === $form->id;
    $accent = $form->theme_color ?: null;
@endphp
<x-layouts.public
    :title="$form->name"
    :description="$form->description ?? 'Isi form '.$form->name"
    :image="$form->banner_image"
>
    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8" style="{{ $fontStyle }}">
        @if ($form->banner_image)
            <div class="mb-6 overflow-hidden rounded-2xl shadow-sm">
                <img src="{{ Storage::url($form->banner_image) }}" alt="{{ $form->name }}" class="h-40 w-full object-cover sm:h-56">
            </div>
        @endif

        @if ($submitted)
            <div
                x-data
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="rounded-2xl border border-emerald-100 bg-white p-8 text-center shadow-sm sm:p-12"
            >
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h1 class="mt-5 font-display text-2xl font-bold text-slate-800">{{ $form->confirmation_title ?: 'Terima kasih!' }}</h1>
                <p class="mx-auto mt-2 max-w-md text-slate-500">{{ $form->confirmation_message ?: 'Responsmu sudah kami terima.' }}</p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    @if ($form->confirmation_link_url)
                        <a href="{{ $form->confirmation_link_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg {{ $accent ? '' : 'bg-gradient-to-r from-primary-500 to-primary-700' }}" @if ($accent) style="background-color: {{ $accent }}" @endif>
                            {{ $form->confirmation_link_label ?: 'Lanjut' }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition-all hover:-translate-y-0.5 hover:border-primary-300 hover:text-primary-600">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @else
            <div class="text-center">
                @if ($form->target)
                    <span class="inline-block rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">Pendaftaran: {{ $form->target->title }}</span>
                @endif
                <h1 class="mt-3 font-display text-3xl font-bold text-slate-800">{{ $form->name }}</h1>
                @if ($form->description)
                    <p class="mx-auto mt-2 max-w-lg text-slate-500">{{ $form->description }}</p>
                @endif
            </div>

            @if ($form->target && ! auth()->check())
                <div class="mt-6 flex items-center gap-3 rounded-xl bg-sunny-50 px-4 py-3 text-sm text-sunny-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    <span><a href="{{ route('login') }}" class="font-semibold underline">Masuk</a> dulu supaya pendaftaranmu otomatis tercatat di akun.</span>
                </div>
            @endif

            @if ($form->fields->isEmpty())
                <div class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <p class="font-display text-lg font-semibold text-slate-700">Form belum punya field</p>
                    <p class="mt-1 text-sm text-slate-500">Admin belum menambahkan pertanyaan ke form ini.</p>
                </div>
            @else
                <div
                    x-data="{
                        submitting: false,
                        values: {{ Illuminate\Support\Js::from($form->fields->mapWithKeys(fn ($f) => [$f->id => $f->type === 'checkbox' ? [] : ''])) }},
                        isValid() {
                            return {{ Illuminate\Support\Js::from($form->fields->where('is_required', true)->pluck('id')) }}
                                .every(id => {
                                    const v = this.values[id];
                                    return Array.isArray(v) ? v.length > 0 : !!v;
                                });
                        },
                    }"
                    class="mt-8 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
                >
                    <form method="POST" action="{{ route('form.submit', $form) }}" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
                        @csrf

                        @foreach ($form->fields as $field)
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                    {{ $field->label }}
                                    @if ($field->is_required) <span class="text-rose-500">*</span> @endif
                                </label>

                                @switch($field->type)
                                    @case('textarea')
                                        <textarea name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200"></textarea>
                                        @break

                                    @case('select')
                                        <select name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                            <option value="">Pilih...</option>
                                            @foreach ($field->options() as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case('radio')
                                        <div class="space-y-2">
                                            @foreach ($field->options() as $option)
                                                <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50">
                                                    <input type="radio" name="field_{{ $field->id }}" value="{{ $option }}" x-model="values[{{ $field->id }}]" class="text-primary-500 focus:ring-primary-400">
                                                    {{ $option }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case('checkbox')
                                        <div class="space-y-2">
                                            @foreach ($field->options() as $option)
                                                <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50">
                                                    <input type="checkbox" name="field_{{ $field->id }}[]" value="{{ $option }}" x-model="values[{{ $field->id }}]" class="rounded text-primary-500 focus:ring-primary-400">
                                                    {{ $option }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @break

                                    @case('file')
                                        <input type="file" name="field_{{ $field->id }}" class="w-full rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-sm text-slate-500 outline-none transition file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-primary-700 hover:border-primary-400">
                                        @break

                                    @case('audio')
                                        <input type="file" name="field_{{ $field->id }}" accept="audio/*,.mp3" class="w-full rounded-xl border-2 border-dashed border-slate-200 px-4 py-3 text-sm text-slate-500 outline-none transition file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-primary-700 hover:border-primary-400">
                                        <p class="mt-1 text-xs text-slate-400">Format MP3/WAV/OGG, maks 10MB.</p>
                                        @break

                                    @case('date')
                                        <input type="date" name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                        @break

                                    @case('email')
                                        <input type="email" name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                        @break

                                    @case('phone')
                                        <input type="tel" name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                        @break

                                    @default
                                        <input type="text" name="field_{{ $field->id }}" x-model="values[{{ $field->id }}]" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-200">
                                @endswitch
                                @error('field_'.$field->id) <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        @endforeach

                        <button type="submit" :disabled="submitting || !isValid()" class="flex w-full items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-playful transition-all hover:-translate-y-0.5 hover:shadow-playful-lg disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 {{ $accent ? '' : 'bg-gradient-to-r from-primary-500 to-primary-700' }}" @if ($accent) style="background-color: {{ $accent }}" @endif>
                            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            <span x-text="submitting ? 'Mengirim...' : 'Kirim'"></span>
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
</x-layouts.public>
