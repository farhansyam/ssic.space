@props(['name', 'value' => ''])

<div
    x-data="{
        html: {{ Illuminate\Support\Js::from($value) }},
        focused: false,
        exec(cmd, val = null) {
            document.execCommand(cmd, false, val);
            this.$refs.editor.focus();
            this.html = this.$refs.editor.innerHTML;
        },
        onInput() { this.html = this.$refs.editor.innerHTML; },
        insertLink() {
            const url = window.prompt('Masukkan URL tautan:', 'https://');
            if (url) this.exec('createLink', url);
        },
    }"
    class="overflow-hidden rounded-xl border bg-slate-50 transition"
    :class="focused ? 'border-primary-500 ring-4 ring-primary-200 bg-white' : 'border-slate-200'"
>
    <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-100/70 px-2 py-1.5">
        <button type="button" @click="exec('bold')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Bold"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h6a3.75 3.75 0 010 7.5h-6v-7.5zm0 7.5h6.75a3.75 3.75 0 010 7.5h-6.75v-7.5z" /></svg></button>
        <button type="button" @click="exec('italic')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Italic"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 4.5h6.75M7.5 19.5h6.75M13.5 4.5l-3 15" /></svg></button>
        <button type="button" @click="exec('underline')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Underline"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5v6.75a5.25 5.25 0 0010.5 0V4.5M5.25 19.5h13.5" /></svg></button>

        <span class="mx-1 h-5 w-px bg-slate-300"></span>

        <button type="button" @click="exec('formatBlock', 'h2')" class="rounded-lg px-2.5 h-8 text-xs font-bold text-slate-600 transition hover:bg-white hover:text-primary-600" title="Heading">H2</button>
        <button type="button" @click="exec('formatBlock', 'h3')" class="rounded-lg px-2.5 h-8 text-xs font-bold text-slate-600 transition hover:bg-white hover:text-primary-600" title="Subheading">H3</button>
        <button type="button" @click="exec('formatBlock', 'blockquote')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Kutipan"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M3 5.25h18M3 12h18M3 18.75h18" /></svg></button>

        <span class="mx-1 h-5 w-px bg-slate-300"></span>

        <button type="button" @click="exec('insertUnorderedList')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Bullet List"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg></button>
        <button type="button" @click="exec('insertOrderedList')" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Numbered List"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.375M3.75 12h.375m-.375 5.25h.375" /></svg></button>
        <button type="button" @click="insertLink()" class="grid h-8 w-8 place-items-center rounded-lg text-slate-600 transition hover:bg-white hover:text-primary-600" title="Tautan"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg></button>

        <span class="mx-1 h-5 w-px bg-slate-300"></span>

        <button type="button" @click="exec('removeFormat'); exec('formatBlock', 'p')" class="rounded-lg px-2.5 h-8 text-xs font-medium text-slate-500 transition hover:bg-white hover:text-rose-600" title="Bersihkan format">Clear</button>
    </div>

    <div
        x-ref="editor"
        contenteditable="true"
        @input="onInput()"
        @focus="focused = true"
        @blur="focused = false"
        x-html="html"
        class="prose prose-sm max-w-none min-h-[220px] px-4 py-3 text-sm text-slate-700 outline-none [&_h2]:font-display [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:mt-3 [&_h3]:font-display [&_h3]:text-base [&_h3]:font-semibold [&_h3]:mt-2 [&_blockquote]:border-l-4 [&_blockquote]:border-primary-300 [&_blockquote]:pl-3 [&_blockquote]:italic [&_blockquote]:text-slate-500 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:text-primary-600 [&_a]:underline"
    ></div>

    <input type="hidden" name="{{ $name }}" x-model="html">
</div>
