@props([
    'name',
    'label' => null,
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
        <label for="{{ $inputId }}" class="block text-xs font-bold uppercase text-slate-700 mb-1">
            {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    <!-- Hidden input to store value for standard form POST -->
    <input type="hidden"
           name="{{ $name }}"
           id="{{ $inputId }}"
           x-model="imageValue"
           @input="updatePreview($event.target.value)"
           @if($required) required @endif>

    <!-- State 1: Kosong (Klik langsung masuk File Manager) -->
    <div x-show="!imageValue"
         @click="$dispatch('open-file-picker', { targetInputId: '{{ $inputId }}', targetPreviewId: '{{ $previewId }}', folder: '{{ $folder }}' })"
         class="group border-2 border-dashed border-slate-300 hover:border-brand-500 bg-slate-50 hover:bg-brand-50/40 rounded-2xl p-5 text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center min-h-[140px]">
        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 group-hover:border-brand-200 group-hover:bg-brand-50 text-slate-400 group-hover:text-brand-600 flex items-center justify-center text-lg shadow-xs transition-colors mb-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <span class="text-xs font-bold text-slate-700 group-hover:text-brand-700 transition-colors">
            Klik untuk Ambil Gambar
        </span>
        <span class="text-[11px] text-slate-400 mt-0.5">
            Langsung masuk File Manager untuk memilih atau unggah baru
        </span>
    </div>

    <!-- State 2: Gambar Terpasang (Visual Preview Card + Tombol Ganti / Hapus) -->
    <div x-show="imageValue" class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 group shadow-xs">
        <!-- Image Container (Klik langsung buka File Manager untuk ganti) -->
        <div @click="$dispatch('open-file-picker', { targetInputId: '{{ $inputId }}', targetPreviewId: '{{ $previewId }}', folder: '{{ $folder }}' })"
             class="relative h-40 sm:h-44 w-full cursor-pointer overflow-hidden flex items-center justify-center bg-slate-950">
            <img :src="previewSrc" id="{{ $previewId }}" alt="Preview" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                <span class="px-3.5 py-1.5 rounded-xl bg-black/75 backdrop-blur-xs text-xs font-bold flex items-center gap-1.5 shadow-md">
                    <i class="fa-solid fa-arrows-rotate"></i> Klik untuk Ganti Gambar
                </span>
            </div>
        </div>

        <!-- Footer Info & Actions -->
        <div class="p-2.5 bg-white border-t border-slate-100 flex items-center justify-between gap-2">
            <div class="min-w-0 flex-1">
                <span class="text-[10px] font-mono text-slate-500 truncate block" x-text="imageValue"></span>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button"
                        @click="$dispatch('open-file-picker', { targetInputId: '{{ $inputId }}', targetPreviewId: '{{ $previewId }}', folder: '{{ $folder }}' })"
                        title="Ganti Gambar dari File Manager"
                        class="px-2.5 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-[11px] font-bold transition flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-folder-open text-xs"></i>
                    <span>Ganti</span>
                </button>
                <button type="button"
                        @click="clearImage()"
                        title="Hapus / Kosongkan Gambar"
                        class="p-1 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 text-xs transition cursor-pointer">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>
    </div>

    @if($help)
        <p class="text-[11px] text-slate-400 leading-normal">{{ $help }}</p>
    @endif
</div>
