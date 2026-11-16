@extends('layouts.admin')

@section('page_title', 'Edit Artikel: ' . $post->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Artikel</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui isi konten artikel dan optimasi SEO.</p>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Judul Artikel *</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $post->category) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">URL Gambar Thumbnail</label>
                    <input type="text" name="thumbnail" value="{{ old('thumbnail', $post->thumbnail) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Isi Konten Artikel *</label>
                <textarea name="content" rows="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono">{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 space-y-3">
                <span class="text-xs font-bold text-brand-900 uppercase">Pengaturan SEO Meta</span>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Meta Title SEO</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Meta Description SEO</label>
                    <textarea name="seo_description" rows="2" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">{{ old('seo_description', $post->seo_description) }}</textarea>
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ $post->is_published ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-0">
                    <span>Terbitkan ke Website (Published)</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Perbarui Artikel
            </button>
        </div>
    </form>
</div>
@endsection
