@extends('layouts.admin')

@section('page_title', 'Pengaturan Website & Profil Perusahaan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Pengaturan Brand, Kontak & Perusahaan</h2>
            <p class="text-xs text-slate-500 mt-1">Sesuaikan nama aplikasi, nomor WhatsApp hotline, nama developer PT, dan media sosial kapan saja.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        @foreach($settings as $group => $items)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 uppercase tracking-wider text-xs text-brand-600 flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Kategori: {{ ucfirst($group) }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($items as $s)
                        <div class="{{ in_array($s->key, ['hero_title', 'hero_subtitle', 'company_address']) ? 'sm:col-span-2' : '' }}">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">
                                {{ $s->label ?? $s->key }}
                            </label>

                            @if(in_array($s->key, ['hero_subtitle', 'company_address']))
                                <textarea name="{{ $s->key }}" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ $s->value }}</textarea>
                            @else
                                <input type="text" name="{{ $s->key }}" value="{{ $s->value }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-sm shadow-md transition">
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
