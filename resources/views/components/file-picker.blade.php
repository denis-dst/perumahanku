@props([
    'name',
    'label' => 'Pilih Foto',
    'value' => '',
    'folder' => 'general',
    'placeholder' => 'uploads/projects/foto.jpg',
    'required' => false,
    'help' => null,
])

@php
    $inputId = 'file_input_' . str_replace(['[', ']', '.'], '_', $name) . '_' . \Illuminate\Support\Str::random(4);
    $previewId = $inputId . '_preview';

    $currentVal = old($name, $value);
    $previewUrl = '';
    if ($currentVal) {
        if (str_starts_with($currentVal, 'http://') || str_starts_with($currentVal, 'https://')) {
            $previewUrl = $currentVal;
        } elseif (str_starts_with($currentVal, 'uploads/') || str_starts_with($currentVal, '/uploads/')) {
            $previewUrl = asset(ltrim($currentVal, '/'));
        } elseif (str_starts_with($currentVal, 'images/') || str_starts_with($currentVal, '/images/')) {
            $previewUrl = asset(ltrim($currentVal, '/'));
        } else {
            $previewUrl = asset('storage/' . $currentVal);
        }
    }
@endphp

<div class="space-y-1.5" x-data="{
    imageValue: '{{ $currentVal }}',
    previewSrc: '{{ $previewUrl }}',
    clearImage() {
        this.imageValue = '';
        this.previewSrc = '';
        const input = document.getElementById('{{ $inputId }}');
        if (input) {
            input.value = '';
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    },
    updatePreview(val) {
        this.imageValue = val;
        if (!val) {
            this.previewSrc = '';
            return;
        }
        if (val.startsWith('http://') || val.startsWith('https://')) {
            this.previewSrc = val;
        } else if (val.startsWith('uploads/') || val.startsWith('/uploads/')) {
            this.previewSrc = '{{ url('/') }}/' + val.replace(/^\/+/, '');
        } else if (val.startsWith('images/') || val.startsWith('/images/')) {
            this.previewSrc = '{{ url('/') }}/' + val.replace(/^\/+/, '');
        } else {
            this.previewSrc = '{{ asset('storage') }}/' + val;
        }
    }
}" @file-picked.window="if ($event.detail.targetInputId === '{{ $inputId }}') { updatePreview($event.detail.file.path); }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-xs font-bold uppercase text-slate-600 mb-1">
            {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    <div class="space-y-2">
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text"
                       name="{{ $name }}"
                       id="{{ $inputId }}"
                       x-model="imageValue"
                       @input="updatePreview($event.target.value)"
                       placeholder="{{ $placeholder }}"
                       @if($required) required @endif
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Open File Manager Modal Button -->
            <button type="button"
                    @click="$dispatch('open-file-picker', { targetInputId: '{{ $inputId }}', targetPreviewId: '{{ $previewId }}', folder: '{{ $folder }}' })"
                    class="px-3.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer">
                <i class="fa-solid fa-folder-open text-xs"></i>
                <span class="hidden sm:inline">Pilih Foto</span>
            </button>

            <!-- Clear Button -->
            <button type="button"
                    x-show="imageValue"
                    @click="clearImage()"
                    title="Kosongkan Foto"
                    class="p-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Preview Thumbnail Box -->
        <div x-show="previewSrc" id="{{ $previewId }}_wrapper" class="flex items-center gap-3 p-2.5 bg-slate-50 rounded-2xl border border-slate-200/80">
            <div class="w-16 h-14 rounded-xl overflow-hidden bg-slate-200 shrink-0 border border-slate-200">
                <img :src="previewSrc" id="{{ $previewId }}" alt="Preview" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0 flex-1 text-[11px] text-slate-500">
                <span class="font-bold text-slate-700 block">Preview Foto Terpilih</span>
                <span class="truncate block font-mono text-[10px] text-brand-600" x-text="imageValue"></span>
            </div>
        </div>

        @if($help)
            <p class="text-[11px] text-slate-400">{{ $help }}</p>
        @endif
    </div>
</div>
