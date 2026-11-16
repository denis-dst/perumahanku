@extends('layouts.admin')

@section('page_title', 'Tulis Artikel Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Form Tulis Artikel Edukasi & SEO</h2>
            <p class="text-xs text-slate-500 mt-1">Tulis konten berkualitas seputar tips properti, KPR, dan panduan legalitas.</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Judul Artikel *</label>
                <input type="text" name="title" required placeholder="Contoh: 5 Tips Memilih Perumahan Dekat Stasiun KRL" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kategori</label>
                    <input type="text" name="category" placeholder="Contoh: Tips KPR, Panduan Beli Rumah" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">URL Gambar Thumbnail</label>
                    <input type="text" name="thumbnail" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Isi Konten Artikel * (HTML format didukung)</label>
                <textarea name="content" rows="10" required placeholder="<p>Tuliskan artikel lengkap di sini...</p>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"></textarea>
            </div>

            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 space-y-3">
                <span class="text-xs font-bold text-brand-900 uppercase">Pengaturan SEO Meta (Google Discoverability)</span>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Meta Title SEO</label>
                    <input type="text" name="seo_title" placeholder="Judul yang muncul di hasil pencarian Google" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Meta Description SEO</label>
                    <textarea name="seo_description" rows="2" placeholder="Ringkasan 1-2 kalimat untuk snippet Google..." class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-0">
                    <span>Langsung Terbitkan ke Website (Published)</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Simpan & Terbitkan Artikel
            </button>
        </div>
    </form>
</div>
@endsection
